<?php

namespace Branzia\Settings\Filament\Page;

use Filament\Pages\Page;
use Branzia\Settings\Services\SettingsRegistry;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'branzia-settings::filament.pages.settings';
    protected static ?int $navigationSort = 1000;

    public function getHeading(): string
    {
        return 'Settings';
    }

    public function getSections(): array
    {
        return SettingsRegistry::getRegisteredPages();
    }


}
