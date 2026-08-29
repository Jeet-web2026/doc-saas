<?php

namespace App\Services\Dashboard;

use App\Models\AgmtDraft;
use App\Models\Agreement;
use Carbon\Carbon;

class DashboardManagementService
{
    public function getTiles(string $orgaUin): array
    {
        $definitions = [
            'available'      => ['label' => 'Agreement Available',      'icon' => 'heroicon-o-document-text',        'color' => 'primary'],
            'expired'        => ['label' => 'Expired Agreement',        'icon' => 'heroicon-o-x-circle',             'color' => 'danger'],
            'active'         => ['label' => 'Active Agreement',         'icon' => 'heroicon-o-check-circle',         'color' => 'success'],
            'new'            => ['label' => 'New Agreement',            'icon' => 'heroicon-o-sparkles',             'color' => 'info'],
            'renew'          => ['label' => 'Renew Agreement',          'icon' => 'heroicon-o-arrow-path',           'color' => 'warning'],
            'sign_pending'   => ['label' => 'Agreement Sign Pending',   'icon' => 'heroicon-o-pencil-square',        'color' => 'warning'],
            'received'       => ['label' => 'Agreement Received',       'icon' => 'heroicon-o-inbox-arrow-down',     'color' => 'success'],
            'draft_available' => ['label' => 'Draft Available',          'icon' => 'heroicon-o-document',             'color' => 'gray'],
            'new_draft'      => ['label' => 'New Draft Added',          'icon' => 'heroicon-o-document-plus',        'color' => 'info'],
        ];


        $tiles = [];
        foreach ($definitions as $key => $meta) {
            $tiles[] = array_merge($meta, [
                'key'   => $key,
                'count' =>  0,
            ]);
        }

        return $tiles;
    }

    public function getCounts(string $orgaUin, int $year, int $month): array
    {
        $base = Agreement::query()->where('Orga_UIN', $orgaUin);
        $draftBase = AgmtDraft::query()->where('Orga_UIN', $orgaUin);
        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        return [
            'available'       => (clone $base)->count(),
            'expired'         => (clone $base)->where('Agmt_Expy_Date', '<', now())->count(),
            'active'          => (clone $base)->where('Agmt_Expy_Date', '>=', now())->count(),
            'new'             => (clone $base)
                ->whereYear('Agmt_Strt_Date', $year)
                ->whereMonth('Agmt_Strt_Date', $month)
                ->count(),
            'renew'           => (clone $base)
                ->whereBetween('Agmt_Expy_Date', [$monthStart, $monthEnd])
                ->count(),
            'sign_pending'    => (clone $base)->where('Stau_UIN', 'sign_pending')->count(),
            'received'        => (clone $base)->where('Stau_UIN', 'received')->count(),
            'draft_available' => (clone $draftBase)->count(),
            'new_draft'       => (clone $draftBase)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
        ];
    }

    public function getTileDetails(): array
    {
        return [
            'available' => 'All agreements currently on file across every status, regardless of month.',
            'expired' => 'Agreements past their end date with no renewal recorded yet.',
            'active' => 'Agreements currently in force and within their valid date range.',
            'new' => 'Agreements created within the selected calendar month.',
            'renew' => 'Agreements flagged for renewal in the selected month.',
            'sign_pending' => 'Agreements sent out and awaiting a counter-party signature.',
            'received' => 'Signed agreements received back and ready for filing.',
            'draft_available' => 'Drafts saved but not yet converted into a live agreement.',
            'new_draft' => 'Drafts added within the selected calendar month.',
        ];
    }
}
