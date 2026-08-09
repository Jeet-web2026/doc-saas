<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Page;

class Drafts extends Page
{
    protected static ?string $navigationLabel = 'Drafts';   

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document';

    protected string $view = 'filament.dashboard.pages.drafts';
}
