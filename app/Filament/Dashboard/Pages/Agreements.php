<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Session;

class Agreements extends Page
{
    protected static string|\BackedEnum|null $navigationIcon =
    'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Agreements';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.dashboard.pages.agreements';

    public string $userUin;

    public string $orgaUin;

    public array $userData;

    public object $companyInformation;

    public function mount(): void
    {
        $this->userUin = Session::get('partaker_user_uin');

        $this->orgaUin = Session::get('selected_orga_uin');

        $this->userData = Session::get('partaker_user_data');

        $this->companyInformation = Session::get('company_information');

        if (
            ! $this->userUin ||
            ! $this->orgaUin ||
            ! $this->userData
        ) {
            redirect()->route('home');
        }
    }
}
