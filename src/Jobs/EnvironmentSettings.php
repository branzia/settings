<?php

namespace Branzia\Settings\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jackiedo\DotenvEditor\DotenvEditor;


class EnvironmentSettings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $envData;

    public function __construct(array $envData)
    {
        $this->envData = $envData;
    }

    public function handle(): void
    {
        $editor = app(DotenvEditor::class);

        foreach ($this->envData as $key => $value) {
            $editor->setKey($key,  $value);
        }

        $editor->save();
        /*
        * Clear and cache config so updated .env values take effect
        */
        foreach ($pageClass::env() as $configKey => $envKey) {
            if (isset($_ENV[$envKey])) {
                config([$configKey => $_ENV[$envKey]]);
            }
        }
        /*
        foreach ($_ENV as $key => $value) {
            config([$key => $value]);
        }*/
    }
}
