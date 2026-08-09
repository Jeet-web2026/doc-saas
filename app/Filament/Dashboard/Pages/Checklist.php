<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;

class Checklist extends Page
{
    protected static ?string $navigationLabel = 'Checklist';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.dashboard.pages.checklist';
    
    protected static bool $shouldRegisterNavigation = false;
}
