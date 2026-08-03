<?php

namespace App\Http\Controllers;

use App\Models\OrganisationDetails;
use App\Models\UserOrgaRela;
use App\Services\Agreement\AgreementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct(
        protected AgreementService $agreementService
    ) {}

    public function index(Request $request)
    {
        $token = $request->query('token');

        if ($token) {
            $userData = $this->fetchUserData($token);

            if (! $userData || empty($userData['data'][0]['User_UIN'])) {
                abort(Response::HTTP_UNAUTHORIZED, 'Unable to verify user with Partakers API.');
            }

            $user = $userData['data'][0];

            Session::put('partaker_user_uin', $user['User_UIN']);
            Session::put('partaker_access_token', $token);
            Session::put('partaker_user_data', $user);

            Session::forget('selected_orga_uin');
        }

        $userUin = Session::get('partaker_user_uin');

        if (! $userUin) {
            abort(Response::HTTP_UNAUTHORIZED, 'No session token found. Please re-open the link with a valid token.');
        }

        $companyInformation = $this->getCompanyInformation($userUin);

        if ($companyInformation->isEmpty()) {
            abort(Response::HTTP_FORBIDDEN, 'No organisations found for this user.');
        }

        if ($companyInformation->count() === 1) {
            Session::put('selected_orga_uin', $companyInformation->first()->Orga_UIN);

            return redirect()->route('all-agreements');
        }

        return view('organisation-select', compact('companyInformation'));
    }

    public function selectOrganisation(Request $request)
    {
        $request->validate([
            'Orga_UIN' => 'required',
        ]);

        $userUin = Session::get('partaker_user_uin');

        if (! $userUin) {
            abort(Response::HTTP_UNAUTHORIZED, 'Session expired. Please re-open the link with a valid token.');
        }

        $isValidOrg = $this->getCompanyInformation($userUin)
            ->contains('Orga_UIN', $request->input('Orga_UIN'));

        if (! $isValidOrg) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have access to that organisation.');
        }

        Session::put('selected_orga_uin', $request->input('Orga_UIN'));

        return redirect()->route('all-agreements');
    }

    public function allAgreements()
    {
        $userUin = Session::get('partaker_user_uin');
        $orgaUin = Session::get('selected_orga_uin');
        $userData = Session::get('partaker_user_data');

        if (! $userUin || ! $orgaUin || ! $userData) {
            return redirect()->route('home');
        }

        $companyInformation = UserOrgaRela::where('User_UIN', $userUin)
            ->with(['organisation' => function ($query) {
                $query->select('Orga_UIN', 'Orga_Name');
            }])
            ->get();

        if (! $companyInformation) {
            abort(Response::HTTP_FORBIDDEN, 'Organisation data not found.');
        }

        return view('welcome', compact('companyInformation', 'userData'));
    }

    public function agreementsData(Request $request): JsonResponse
    {
        $userUin = Session::get('partaker_user_uin');
        $orgaUin = Session::get('selected_orga_uin');

        if (! $userUin || ! $orgaUin) {
            return response()->json(['message' => 'No organisation selected.'], Response::HTTP_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'search'           => 'nullable|string|max:255',
            'date_from'        => 'nullable|date',
            'date_to'          => 'nullable|date|after_or_equal:date_from',
            'date_filter_type' => 'nullable|string|in:agreement_date,expiry_date',
            'per_page'         => 'nullable|integer|min:1|max:100',
            'page'             => 'nullable|integer|min:1',
        ]);

        $agreements = $this->agreementService->getAgreements(
            search: $validated['search'] ?? null,
            dateFrom: $validated['date_from'] ?? null,
            dateTo: $validated['date_to'] ?? null,
            dateFilterType: $validated['date_filter_type'] ?? 'agreement_date',
            perPage: $validated['per_page'] ?? 5,
            companyId: $orgaUin,
        );

        return response()->json($agreements);
    }

    private function getCompanyInformation($userUin)
    {
        $organisationUIN = UserOrgaRela::where('User_UIN', $userUin)
            ->pluck('Orga_UIN')
            ->values();

        return OrganisationDetails::whereIn('Orga_UIN', $organisationUIN)->get();
    }

    private function fetchUserData(string $token): ?array
    {
        try {
            $response = Http::timeout(10)
                ->post('https://api.partakers.in/User/getUserData', [
                    'token' => $token,
                ]);

            if ($response->failed()) {
                Log::warning('Partakers getUserData failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('Partakers getUserData exception: ' . $e->getMessage());

            return null;
        }
    }
}
