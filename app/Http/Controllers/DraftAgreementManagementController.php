<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agreement\StoreAgreementrequest;
use App\Models\OrganisationDetails;
use App\Services\Agreement\AgreementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DraftAgreementManagementController extends Controller{

    public function __construct(private readonly AgreementService $agreementService) {}

    public function index(Request $request): View
    {
        $agreementCategories = $this->agreementService->fetchAgreementCategories();
        $userData = $this->agreementService->fetchUserData(Session::get('partaker_access_token'));
        $companyInformation =  OrganisationDetails::where('Orga_UIN', $request->query('company'))->first();
        return view('agreements.draft-agreement', compact('agreementCategories', 'userData', 'companyInformation'));
    }
}