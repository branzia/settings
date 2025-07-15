<?php

namespace Branzia\Settings\Contracts;

use Filament\Forms\Components\Field;

abstract class SettingsPage
{
    public static string $navigationGroup = 'General';
    public static string $navigationLabel = 'Settings';

    public static function getFormSchema(): array
    {
        return [];
    }
}
