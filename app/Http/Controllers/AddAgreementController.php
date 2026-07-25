<?php

namespace App\Http\Controllers;

use App\Http\Requests\Agreement\StoreAgreementrequest;
use App\Services\Agreement\AgreementService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AddAgreementController extends Controller
{
    public function __construct(private readonly AgreementService $agreementService) {}

    public function index(): View
    {
        $agreementCategories = $this->agreementService->fetchAgreementCategories();
        return view('agreements.add-agreement', compact('agreementCategories'));
    }

    public function getAgreementTypes(int $categoryId): JsonResponse
    {
        return response()->json($this->agreementService->getAgreementTypes($categoryId));
    }

    public function store(StoreAgreementrequest $request)
    {
        $this->agreementService->store($request);

        return redirect()
            ->route('home')
            ->with('success', 'Agreement created successfully.');
    }
}
