<?php

namespace Branzia\Settings\Services;

use Branzia\Settings\Contracts\SettingsPage;

class SettingsRegistry
{
    protected static array $pages = [];

    public static function push(SettingsPage|string $page): void
    {
        static::$pages[] = $page;
    }

    public static function getRegisteredPages(): array
    {
        return collect(static::$pages)
            ->sortBy(fn ($class) => $class::$sort ?? 0)
            ->values()
            ->all();
    }
}