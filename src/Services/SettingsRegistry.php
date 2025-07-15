<?php

namespace Branzia\Settings\Services;

use Branzia\Settings\Contracts\SettingsPage;

class SettingsRegistry
{
    public static function getRegisteredPages(): array
    {
        return collect(get_declared_classes())
            ->filter(fn ($class) => is_subclass_of($class, SettingsPage::class))
            ->filter(fn ($class) => !(new \ReflectionClass($class))->isAbstract())
            ->values()
            ->all();
    }
}
