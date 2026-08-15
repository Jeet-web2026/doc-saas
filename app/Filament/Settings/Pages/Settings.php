<?php

namespace App\Filament\Settings\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Session;

class Settings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Agreement Management';
    protected static string|\UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.settings.pages.settings';

    public ?string $userUin = null;
    public ?string $orgaUin = null;

    public int $totalAgreements = 0;
    public int $totalDrafts = 0;
    public int $expiringSoon = 0;
    public int $expiredCount = 0;

    public array $categoryBreakdown = [];

    public function mount(): void
    {
        $this->userUin = Session::get('partaker_user_uin');
        $this->orgaUin = Session::get('partaker_orga_uin');

        abort_if(! $this->userUin, 419, 'Session expired.');

        // TODO: load agreement-management data here
    }
}
