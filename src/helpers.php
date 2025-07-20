<?php

use Branzia\Settings\Models\Setting;

if (!function_exists('settings')) {
    function settings(string $key = null, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}
