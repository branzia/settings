<?php

namespace Branzia\Settings;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Branzia\Settings\Services\SettingsRegistry;
class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'branzia-settings');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/branzia-settings'),
        ], 'settings-views');
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'app-settings');

        SettingsRegistry::push(\Branzia\Settings\Filament\Settings\EnvironmentSchema::class);
    }
}
