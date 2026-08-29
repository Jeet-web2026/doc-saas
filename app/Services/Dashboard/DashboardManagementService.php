<?php

namespace App\Services\Dashboard;

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

        $counts = $this->getCounts($orgaUin);

        $tiles = [];
        foreach ($definitions as $key => $meta) {
            $tiles[] = array_merge($meta, [
                'key'   => $key,
                'count' => $counts[$key] ?? 0,
            ]);
        }

        return $tiles;
    }

    protected function getCounts(string $orgaUin): array
    {
        $base = Agreement::query()->where('Orga_UIN', $orgaUin);

        return [
            'available'       => (clone $base)->count(),
            'expired'         => (clone $base)->where('Agmt_Expy_Date', '<', now())->count(),
            'active'          => (clone $base)->where('Agmt_Expy_Date', '>=', now())->count(),
            'new'             => 2,
            'renew'           => (clone $base)->whereBetween('Agmt_Expy_Date', [now(), now()->addDays(30)])->count(),
            'sign_pending'    => (clone $base)->where('Stau_UIN', 'sign_pending')->count(),
            'received'        => (clone $base)->where('Stau_UIN', 'received')->count(),
            'draft_available' => (clone $base)->where('Stau_UIN', 'draft')->count(),
            'new_draft'       => (clone $base)->where('Stau_UIN', 'draft')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
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

    /**
     * Month-wise calendar payload: per-day created/renewal flags + a sorted event list.
     */
    public function getCalendarData(string $orgaUin, int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $created = Agreement::query()
            ->where('orga_uin', $orgaUin)
            ->whereBetween('created_at', [$start, $end])
            ->get(['id', 'name', 'type', 'created_at as date']);

        $expiring = Agreement::query()
            ->where('orga_uin', $orgaUin)
            ->whereBetween('expiry_date', [$start, $end])
            ->get(['id', 'name', 'type', 'expiry_date as date']);

        $days = [];
        $events = [];

        foreach ($created as $a) {
            $d = Carbon::parse($a->date)->toDateString();
            $days[$d]['created'] = true;
            $events[] = ['date' => $d, 'type' => 'new', 'name' => $a->name, 'type_label' => $a->type];
        }

        foreach ($expiring as $a) {
            $d = Carbon::parse($a->date)->toDateString();
            $days[$d]['renewal'] = true;
            $events[] = ['date' => $d, 'type' => 'renewal', 'name' => $a->name, 'type_label' => $a->type];
        }

        usort($events, fn($a, $b) => $a['date'] <=> $b['date']);

        return [
            'year' => $year,
            'month' => $month,
            'title' => $start->format('F Y'),
            'first_day_of_week' => $start->copy()->startOfMonth()->dayOfWeek, // 0 = Sun
            'days_in_month' => $start->daysInMonth,
            'days' => $days,
            'events' => $events,
            'events_count' => count($events),
        ];
    }
}
