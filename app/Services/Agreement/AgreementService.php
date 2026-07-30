<?php

namespace App\Services\Agreement;

use App\Http\Requests\Agreement\RenewAgreementRequest;
use App\Http\Requests\Agreement\StoreAgreementrequest;
use App\Models\Agreement;
use App\Models\AgreementCategories;
use App\Models\AgreementType;
use App\Models\TranAgmtRemk;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgreementService
{
    public function fetchAgreementCategories(): ?Collection
    {
        return AgreementCategories::with(['children' => function ($query) {
            $query->orderBy('Agmt_Catg_Name', 'asc');
        }])
            ->select('Agmt_Catg_UIN', 'Agmt_Catg_Name')
            ->get();
    }

    public function getAgreementTypes(int $categoryId): ?Collection
    {
        return AgreementType::where('Agmt_Catg_UIN', $categoryId)
            ->select('Agmt_Type_UIN', 'Agmt_Type_Name')
            ->orderBy('Agmt_Type_Name', 'asc')
            ->get();
    }

    public function store(StoreAgreementrequest $request): Agreement
    {
        return DB::transaction(function () use ($request) {

            $attachment = null;
            $attachmentSizeKb = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = $this->storeAgreementAttachment($file);
                $attachmentSizeKb = $this->getFileSizeInKb($file);
            }

            $agreement = Agreement::create([
                'Orga_UIN'       => $request->company_id,
                'User_UIN'       => Auth::id() ?? 1,

                'Agmt_Catg_UIN'  => $request->agreement_category,
                'Agmt_Type_UIN'  => $request->agreement_type,
                'Agmt_Name'      => $request->agreement_name,

                'Agmt_File_no'        => $request->file_no,

                'Agmt_Strt_Date' => $request->agreement_date,
                'Agmt_Expy_Date' => $request->expiry_date,
                'Agmt_Sign_Date' => $request->signing_date,
                'Agmt_Ntry_Date' => $request->notary_date,

                'Agmt_Vson'      => $request->agreement_version,
                'Agmt_Drft_By'   => $request->drafted_by,
                'Agmt_Judn'      => $request->jurisdiction,
                'Agmt_Atch'      => $attachment,
                'Agmt_Size_KB'  => $attachmentSizeKb,

                'Agmt_Parn_UIN'  => null,
                'Agmt_Prty_UIN'  => null,

                'CrBy'           => Auth::id() ?? 1,
                'MoBy'           => Auth::id() ?? 1,

                'Stau_UIN'       => $this->determineStatusUin($request->expiry_date),
            ]);

            $remarks = trim($request->remarks);

            if (
                $remarks !== '<p><br></p>' &&
                !empty(trim(strip_tags($remarks)))
            ) {
                TranAgmtRemk::create([
                    'agmt_uin'       => $agreement->Agmt_UIN,
                    'agmt_remk_date' => now()->toDateString(),
                    'agmt_remk'      => $remarks,
                    'stau_uin'       => 1,
                    'crby'           => Auth::id() ?? 1,
                    'moby'           => Auth::id() ?? 1,
                ]);
            }

            $primaryPartyUin = null;

            foreach ($request->input('parties', []) as $index => $partyData) {

                $party = $agreement->parties()->create([
                    'Agmt_Prty_No'       => $partyData['role'] ?? null,
                    'Agmt_Prty_Org_Name' => $partyData['org_name'] ?? null,
                    'Agmt_Athr_Sgnt'     => $partyData['signatory'] ?? null,
                    'CrBy'               => Auth::id() ?? 1,
                    'MoBy'               => Auth::id() ?? 1,
                ]);

                if (isset($partyData['is_primary'])) {
                    $primaryPartyUin = $party->Agmt_Prty_UIN;
                }
            }

            if ($primaryPartyUin !== null) {
                $agreement->Agmt_Prty_UIN = $primaryPartyUin;
                $agreement->saveQuietly();
            }

            return $agreement->load('parties');
        });
    }

    public function getAgreements(
        ?string $search = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $dateFilterType = 'agreement_date',
        int $perPage = 5,
        ?int $companyId = null
    ) {
        $query = Agreement::query()
            ->select([
                'Agmt_UIN',
                'Agmt_Name',
                'Agmt_Strt_Date',
                'Agmt_Expy_Date',
                'Agmt_Sign_Date',
                'Agmt_Ntry_Date',
                'Agmt_Vson',
                'Agmt_Drft_By',
                'Agmt_Judn',
                'Agmt_Remk',
                'Agmt_File_no',
                'Stau_UIN',
                'Orga_UIN',
                'Agmt_Catg_UIN',
                'Agmt_Type_UIN',
                'Agmt_Parn_UIN',
            ])
            ->with([
                'category:Agmt_Catg_UIN,Agmt_Catg_Name',
                'type:Agmt_Type_UIN,Agmt_Type_Name',
                'parties:Agmt_UIN,Agmt_Prty_No,Agmt_Prty_Org_Name,Agmt_Athr_Sgnt',
                'parentAgreement:Agmt_UIN,Agmt_Name,Agmt_File_no',
                'remarks' => function ($q) {
                    $q->select('agmt_remk_uin', 'agmt_uin', 'agmt_remk', 'agmt_remk_date')
                        ->orderBy('agmt_remk_date');
                },
            ])
            ->withCount('childAgreements')
            ->whereIn('Stau_UIN', ['active', 'renewed', 'expired']);

        if ($companyId) {
            $query->where('Orga_UIN', $companyId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Agmt_Name', 'like', "%{$search}%")
                    ->orWhere('Agmt_File_no', 'like', "%{$search}%")
                    ->orWhereHas('type', function ($q2) use ($search) {
                        $q2->where('Agmt_Type_Name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('Agmt_Catg_Name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('parties', function ($q2) use ($search) {
                        $q2->where('Agmt_Prty_Org_Name', 'like', "%{$search}%");
                    });
            });
        }

        if ($dateFrom && $dateTo) {
            $dateColumn = $dateFilterType === 'expiry_date' ? 'Agmt_Expy_Date' : 'Agmt_Strt_Date';
            $query->whereBetween($dateColumn, [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ]);
        }

        $agreements = $query->latest('Agmt_UIN')->paginate($perPage);

        $agreements->getCollection()->transform(function ($agreement) {
            $expiryDate = $agreement->Agmt_Expy_Date;

            return [
                'id' => (string) $agreement->Agmt_UIN,
                'category' => optional($agreement->category)->Agmt_Catg_Name,
                'type' => optional($agreement->type)->Agmt_Type_Name,
                'name' => ucfirst($agreement->Agmt_Name),

                'parties' => $agreement->parties->map(function ($party) {
                    return [
                        'role'      => $party->Agmt_Prty_No,
                        'org'       => $party->Agmt_Prty_Org_Name,
                        'signatory' => $party->Agmt_Athr_Sgnt,
                    ];
                })->values(),

                'date' => optional($agreement->Agmt_Strt_Date)?->format('Y-m-d') ?? '-',
                'expiry' => optional($agreement->Agmt_Expy_Date)?->format('Y-m-d') ?? '-',
                'signingDate' => optional($agreement->Agmt_Sign_Date)?->format('Y-m-d') ?? '-',
                'notaryDate' => optional($agreement->Agmt_Ntry_Date)?->format('Y-m-d') ?? '-',

                'version' => $agreement->Agmt_Vson ?? '-',
                'drafted' => $agreement->Agmt_Drft_By ?? '-',

                'parentAgreement' => $agreement->parentAgreement ? [
                    'id'   => $agreement->parentAgreement->Agmt_UIN ?? '-',
                    'name' => $agreement->parentAgreement->Agmt_Name ?? '-',
                    'file_no' => $agreement->parentAgreement->Agmt_File_no ?? '-',
                ] : null,

                'daysLeft' => $expiryDate ? (int) now()->diffInDays($expiryDate, false) : null,

                'categoryLabel' => optional($agreement->category)->Agmt_Catg_Name ?? '-',
                'jurisdiction' => $agreement->Agmt_Judn ?? '-',
                'remarks' => $agreement->remarks->pluck('agmt_remk')->implode(', ') ?? '-',
                'fileNo' => $agreement->Agmt_File_no ?? '-',
            ];
        });

        return $agreements;
    }

    public function getAgreementById(int $agreementId): Agreement
    {
        return Agreement::with([
            'category',
            'type',
            'parties',
            'remarks'
        ])->findOrFail($agreementId);
    }

    public function updateAgreement(int $agreementId, Request $request): Agreement
    {
        return DB::transaction(function () use ($agreementId, $request) {

            $agreement = Agreement::findOrFail($agreementId);
            $attachment = $agreement->Agmt_Atch;
            $attachmentSizeKb = $agreement->Agmt_Size_KB;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = $this->storeAgreementAttachment($file);
                $attachmentSizeKb = $this->getFileSizeInKb($file);

                if ($agreement->Agmt_Atch) {
                    Storage::disk('public')->delete($agreement->Agmt_Atch);
                }
            }

            $updatePayload = [
                'Agmt_Catg_UIN'  => $request->category,
                'Agmt_Type_UIN'  => $request->type,
                'Agmt_Name'      => $request->name,
                'Agmt_File_no'        => $request->file_no,
                'Agmt_Strt_Date' => $request->agreement_date,
                'Agmt_Expy_Date' => $request->expiry_date,
                'Agmt_Sign_Date' => $request->signing_date,
                'Agmt_Ntry_Date' => $request->notary_date,
                'Agmt_Vson'      => $request->version,
                'Agmt_Drft_By'   => $request->drafted_by,
                'Agmt_Judn'      => $request->jurisdiction,
                'MoBy'           => Auth::id() ?? 1,
                'Agmt_Atch'      => $attachment,
                'Agmt_Size_KB'  => $attachmentSizeKb,
                'Stau_UIN'       => $request->expiry_date ? $this->determineStatusUin($request->expiry_date) : $this->determineStatusUin($agreement->expiry_date),
            ];

            if ($request->filled('remarks')) {
                TranAgmtRemk::create([
                    'agmt_uin'       => $agreement->Agmt_UIN,
                    'agmt_remk_date' => now()->toDateString(),
                    'agmt_remk'      => $request->remarks,
                    'stau_uin'       => 1,
                    'crby'           => Auth::id() ?? 1,
                    'moby'           => Auth::id() ?? 1,
                ]);
            }

            $agreement->update($updatePayload);

            $agreement->increment('update_count');

            $submittedPartyIds = [];
            $primaryPartyUin = null;

            foreach ($request->input('parties', []) as $index => $partyData) {

                $partyId = $partyData['id'] ?? null;

                $attributes = [
                    'Agmt_Prty_No'       => $partyData['role'] ?? null,
                    'Agmt_Prty_Org_Name' => $partyData['org_name'] ?? null,
                    'Agmt_Athr_Sgnt'     => $partyData['signatory'] ?? null,
                    'MoBy'               => Auth::id() ?? 1,
                ];

                if ($partyId) {
                    $party = $agreement->parties()->where('Agmt_Prty_UIN', $partyId)->first();

                    if ($party) {
                        $party->update($attributes);
                        $submittedPartyIds[] = $party->Agmt_Prty_UIN;
                    }
                } else {
                    $party = $agreement->parties()->create(array_merge($attributes, [
                        'CrBy' => Auth::id() ?? 1,
                        'MoBy' => Auth::id() ?? 1,
                    ]));
                    $submittedPartyIds[] = $party->Agmt_Prty_UIN;
                }

                // Checkbox only submits when checked
                if (isset($partyData['is_primary']) && isset($party)) {
                    $primaryPartyUin = $party->Agmt_Prty_UIN;
                }
            }

            // Remove parties that existed before but were deleted in the form
            $agreement->parties()
                ->whereNotIn('Agmt_Prty_UIN', $submittedPartyIds)
                ->delete();

            // Update primary party reference
            if ($primaryPartyUin !== null) {
                $agreement->update(['Agmt_Prty_UIN' => $primaryPartyUin]);
            }

            return $agreement->fresh(['parties']);
            /**
             * Attachment update
             * We'll implement after file upload integration.
             */
        });
    }

    public function renewAgreement(int $agreementId, RenewAgreementRequest $request): Agreement
    {
        return DB::transaction(function () use ($agreementId, $request) {

            $oldAgreement = Agreement::with('parties')->findOrFail($agreementId);

            if ($oldAgreement->childAgreements()->exists()) {
                throw new Exception('This agreement has already been renewed.');
            }

            // 1. Mark the old agreement inactive
            $oldAgreement->update([
                'Stau_UIN' => 'renewed',
                'MoBy'     => Auth::id() ?? 1,
            ]);

            // 2. Handle new attachment if uploaded, otherwise carry over the old one
            $attachment = $oldAgreement->Agmt_Atch;
            $attachmentSizeKb = $oldAgreement->Agmt_Size_KB;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment =  $this->storeAgreementAttachment($file);
                $attachmentSizeKb = $this->getFileSizeInKb($file);
            }

            // 3. Create the new agreement as a copy of the old one, overriding only expiry (+ optional remarks/attachment)
            $newAgreement = Agreement::create([
                'Orga_UIN'       => $oldAgreement->Orga_UIN,
                'User_UIN'       => $oldAgreement->User_UIN,

                'Agmt_Catg_UIN'  => $request->agreement_category ?? $oldAgreement->Agmt_Catg_UIN,
                'Agmt_Type_UIN'  => $request->agreement_type ?? $oldAgreement->Agmt_Type_UIN,
                'Agmt_Name'      => $request->agreement_name ?? $oldAgreement->Agmt_Name,

                'Agmt_File_no'        => $request->file_no ?? $oldAgreement->Agmt_File_no,

                'Agmt_Strt_Date' => $request->agreement_date ?? $oldAgreement->Agmt_Strt_Date,
                'Agmt_Expy_Date' => $request->expiry_date ?? $oldAgreement->expiry_date, // ← the only date field that changes
                'Agmt_Sign_Date' => $request->signing_date ?? $oldAgreement->Agmt_Sign_Date,
                'Agmt_Ntry_Date' => $request->notary_date ?? $oldAgreement->Agmt_Ntry_Date,

                'Agmt_Vson'      => $request->agreement_version ?? $oldAgreement->Agmt_Vson,
                'Agmt_Drft_By'   => $request->drafted_by ?? $oldAgreement->Agmt_Drft_By,
                'Agmt_Judn'      => $request->jurisdiction ?? $oldAgreement->Agmt_Judn,
                'Agmt_Atch'      => $attachment,
                'Agmt_Size_KB'  => $attachmentSizeKb,

                'Agmt_Parn_UIN'  => $oldAgreement->Agmt_UIN, // link back to the original agreement
                'Agmt_Prty_UIN'  => null, // set after parties are copied

                'CrBy'           => Auth::id() ?? 1,
                'MoBy'           => Auth::id() ?? 1,

                'Stau_UIN'       => $request->expiry_date ? $this->determineStatusUin($request->expiry_date) : $this->determineStatusUin($oldAgreement->expiry_date),
            ]);

            if ($request->filled('remarks')) {
                TranAgmtRemk::create([
                    'agmt_uin'       => $newAgreement->Agmt_UIN,
                    'agmt_remk_date' => now()->toDateString(),
                    'agmt_remk'      => $request->remarks,
                    'stau_uin'       => 1,
                    'crby'           => Auth::id() ?? 1,
                    'moby'           => Auth::id() ?? 1,
                ]);
            }

            // 4. Copy parties over to the new agreement
            $primaryPartyUin = null;

            foreach ($request->input('parties', []) as $index => $partyData) {

                $party = $newAgreement->parties()->create([
                    'Agmt_Prty_No'       => $partyData['role'] ?? null,
                    'Agmt_Prty_Org_Name' => $partyData['org_name'] ?? null,
                    'Agmt_Athr_Sgnt'     => $partyData['signatory'] ?? null,
                    'CrBy'               => Auth::id() ?? 1,
                    'MoBy'               => Auth::id() ?? 1,
                ]);

                if (isset($partyData['is_primary'])) {
                    $primaryPartyUin = $party->Agmt_Prty_UIN;
                }
            }

            if ($primaryPartyUin !== null) {
                $newAgreement->update(['Agmt_Prty_UIN' => $primaryPartyUin]);
            }

            return $newAgreement->load('parties');
        });
    }

    public function getHistory(int $agreementId)
    {
        $agreement = Agreement::findOrFail($agreementId);

        return $agreement->getRenewalHistory()->map(function ($item) {
            return [
                'id'          => $item->Agmt_UIN,
                'name'        => $item->Agmt_Name,
                'status'      => $item->Stau_UIN,
                'agmt_date'   => optional($item->Agmt_Sign_Date)->format('d M Y'),
                'expiry_date' => optional($item->Agmt_Expy_Date)->format('d M Y'),
                'created_date' => optional($item->created_at)->format('d M Y'),
                'file_no'     => $item->Agmt_File_no,
            ];
        });
    }

    private function determineStatusUin(?string $expiryDate): string
    {
        if (!$expiryDate) {
            return 'active';
        }

        $expiry = Carbon::parse($expiryDate)->startOfDay();
        $today  = Carbon::today();

        return $expiry->lt($today) ? 'expired' : 'active';
    }

    private function storeAgreementAttachment($file): string
    {
        $systemNumber = $this->generateSystemNumber();

        $originalName = $file->getClientOriginalName();

        $fileName = $systemNumber . '_' . $originalName;

        return $file->storeAs('agreements', $fileName, 'public');
    }

    private function generateSystemNumber(): string
    {
        return time() . rand(1000, 9999);
    }

    private function getFileSizeInKb(UploadedFile $file): int
    {
        return (int) round($file->getSize() / 1024);
    }
}
