<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\AgreementCategories;
use App\Models\AgreementType;
use App\Services\Agreement\AgreementService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ViewAgreementController extends Controller
{
    protected AgreementService $agreementService;

    public function __construct(AgreementService $agreementService)
    {
        $this->agreementService = $agreementService;
    }

    public function index(int $agreementId)
    {
        $agreement = $this->agreementService->getAgreementById($agreementId);

        $agreementCategories = AgreementCategories::with('children')
            ->get();

        $types = AgreementType::all();

        return view('agreements.view-agreement', compact('agreement', 'agreementCategories', 'types'));
    }

    public function history(int $agreementId)
    {
        $agreement = $this->agreementService->getHistory($agreementId);

        return response()->json(['data' => $agreement]);
    }

    public function destroy(Agreement $agreement)
    {
        $filePath = $agreement->file_path;
        DB::transaction(function () use ($agreement) {
            Agreement::where('Agmt_Parn_UIN', $agreement->Agmt_UIN)
                ->update(['Agmt_Parn_UIN' => null]);

            $agreement->delete();
        });

        if ($filePath && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        return redirect()->back()->with('success', 'Agreement deleted successfully.');
    }

    public function generateViewLink(Agreement $agreement)
    {
        $url = $this->agreementService->generateViewLink($agreement);

        return response()->json([
            'url' => $url,
        ]);
    }

    public function publicView(string $token)
    {
        $link = $this->agreementService->resolveShareLink($token);

        return match ($link->Link_Type) {
            'agreement' => view('agreements.public-view', ['agreement' => $link->foreign]),
            'draft'     => view('agreements.draft-public-view', ['draft' => $link->foreign]),
            default     => abort(404),
        };
    }
}
