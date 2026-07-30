<?php

namespace App\Http\Controllers;

use App\Models\OrganisationDetails;
use App\Services\Agreement\AgreementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(
        protected AgreementService $agreementService
    ) {}

    public function index()
    {
        $companyInformation = Auth::user()
            ->organisations()
            ->select('tbl_mast_orga.Orga_Type_UIN', 'tbl_mast_orga.Orga_Name')
            ->orderBy('tbl_mast_orga.Orga_Type_UIN', 'desc')
            ->get();
        return view('welcome', compact('companyInformation'));
    }

    public function allAgreements(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search'           => 'nullable|string|max:255',
            'date_from'        => 'nullable|date',
            'date_to'          => 'nullable|date|after_or_equal:date_from',
            'date_filter_type' => 'nullable|string|in:agreement_date,expiry_date',
            'per_page'         => 'nullable|integer|min:1|max:100',
            'page'             => 'nullable|integer|min:1',
            'company_id'       => 'nullable|integer',
        ]);

        $agreements = $this->agreementService->getAgreements(
            search: $validated['search'] ?? null,
            dateFrom: $validated['date_from'] ?? null,
            dateTo: $validated['date_to'] ?? null,
            dateFilterType: $validated['date_filter_type'] ?? 'agreement_date',
            perPage: $validated['per_page'] ?? 5,
            companyId: $validated['company_id'] ?? null,
        );

        return response()->json($agreements);
    }
}
