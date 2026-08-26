<?php

namespace App\Filament\Dashboard\Pages;

use App\Filament\Dashboard\Pages\Concerns\HasCompanyHeader;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Session;

class Dashboard extends Page
{
    use HasCompanyHeader;
    // Icon shown next to the nav label
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    // Label shown in the left menu
    protected static ?string $navigationLabel = 'Dashboard';

    // Negative sort pins it above every other nav item (default items are usually 0+)
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.dashboard.pages.dashboard';

    // protected static ?string $slug = '';

    // Static demo data for now — wire these to real Agreement queries later.
    public function getTiles(): array
    {
        return [
            [
                'key' => 'available',
                'label' => 'Agreement Available',
                'count' => 128,
                'icon' => 'heroicon-o-document-text',
                'color' => 'primary',
            ],
            [
                'key' => 'expired',
                'label' => 'Expired Agreement',
                'count' => 14,
                'icon' => 'heroicon-o-x-circle',
                'color' => 'danger',
            ],
            [
                'key' => 'active',
                'label' => 'Active Agreement',
                'count' => 92,
                'icon' => 'heroicon-o-check-circle',
                'color' => 'success',
            ],
            [
                'key' => 'new',
                'label' => 'New Agreement',
                'count' => 7,
                'icon' => 'heroicon-o-sparkles',
                'color' => 'info',
            ],
            [
                'key' => 'renew',
                'label' => 'Renew Agreement',
                'count' => 9,
                'icon' => 'heroicon-o-arrow-path',
                'color' => 'warning',
            ],
            [
                'key' => 'sign_pending',
                'label' => 'Agreement Sign Pending',
                'count' => 21,
                'icon' => 'heroicon-o-pencil-square',
                'color' => 'warning',
            ],
            [
                'key' => 'received',
                'label' => 'Agreement Received',
                'count' => 45,
                'icon' => 'heroicon-o-inbox-arrow-down',
                'color' => 'success',
            ],
            [
                'key' => 'draft_available',
                'label' => 'Draft Available',
                'count' => 33,
                'icon' => 'heroicon-o-document',
                'color' => 'gray',
            ],
            [
                'key' => 'new_draft',
                'label' => 'New Draft Added',
                'count' => 5,
                'icon' => 'heroicon-o-document-plus',
                'color' => 'info',
            ],
        ];
    }

    // Static demo "short review" content shown when a tile is clicked.
    // Replace with a real query keyed by the selected month/tile later.
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

    public string $userUin;
    public string $orgaUin;

    public function mount(): void
    {
        $this->userUin = Session::get('partaker_user_uin');
        $this->orgaUin = Session::get('selected_orga_uin');

        if (! $this->userUin || ! $this->orgaUin || ! $this->userData) {
            $this->redirect(route('home'));
        }
    }
}
