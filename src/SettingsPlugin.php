<?php

namespace Branzia\Settings;

use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingsPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'branzia-settings';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            \Branzia\Settings\Filament\Page\Settings::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // You can optionally resolve settings here or listen to events
    }
}
