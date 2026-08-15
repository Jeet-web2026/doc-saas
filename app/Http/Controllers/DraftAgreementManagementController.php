<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDraftAgreementRequest;
use App\Models\OrganisationDetails;
use App\Services\Agreement\AgreementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DraftAgreementManagementController extends Controller
{

    public function __construct(private readonly AgreementService $agreementService) {}

    public function index(Request $request): View
    {
        $agreementCategories = $this->agreementService->fetchAgreementCategories();
        $userData = $this->agreementService->fetchUserData(Session::get('partaker_access_token'));
        $companyInformation =  OrganisationDetails::where('Orga_UIN', $request->query('company'))->first();
        return view('agreements.draft-agreement', compact('agreementCategories', 'userData', 'companyInformation'));
    }

    public function store(StoreDraftAgreementRequest $request): RedirectResponse
    {
        $this->agreementService->storeDraftAgreement(
            $request->validated(),
            Session::get('partaker_access_token'),
            $request->file('draft_file'),
            $request->file('checklist_file'),
        );

        return redirect()
            ->route('home')
            ->with('success', 'Agreement draft saved successfully.');
    }
}
