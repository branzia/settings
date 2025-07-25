<?php

namespace Branzia\Settings\Filament\Page;


use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Filament\Forms\Components\Component;
use Branzia\Settings\Jobs\EnvironmentSettings;
use Branzia\Settings\Services\SettingsRegistry;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'branzia-settings::filament.pages.settings';
    protected static ?int $navigationSort = 1000;

    public array $formData = [];
    public ?string $activeSetting = null;
    
    public function mount(): void
    { 
        $this->activeSetting = $this->activeSetting ?? SettingsRegistry::getRegisteredPages()[0] ?? null;
        $filled = [];
        foreach (SettingsRegistry::getRegisteredPages() as $pageClass) {
            if (method_exists($pageClass, 'env')) {
                $data = $pageClass::env(); 
                if (is_array($data)) {
                    $evaluated = [];
                    foreach ($data as $configKey => $value) {
                        $evaluated[$configKey] = env($value);
                    }
                    $filled = array_merge($filled, $evaluated);
                }
            }
        }
   
        $settings = \Branzia\Settings\Models\Setting::pluck('value', 'key')->map(function ($value) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $value;
        })->toArray();
        $this->formData = \Illuminate\Support\Arr::undot(array_merge($settings,$filled));
        $this->form->fill($this->formData); 
    }

    protected function getFormStatePath(): string
    {
        return 'formData';
    }
    public function getSidebarGroups(): Collection
    {
        return collect(SettingsRegistry::getRegisteredPages())->groupBy(fn ($class) => $class::$group ?? 'General')->map(function ($pages, $group) {
                return collect($pages)->sortBy(fn ($class) => $class::$sort ?? 0);
        });
    }
    public function form(Form $form): Form {
        return $form->schema($this->getActiveSchema())->statePath('formData');
    }

    public function getActiveSchema(): array
    {
        if (! $this->activeSetting || ! class_exists($this->activeSetting)) {
            return [];
        }

        return $this->activeSetting::getFormSchema();
    }

     public static function getNavigationLabel(): string
    {
        return 'Settings';
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return collect($data)->dot()->all();
    }
    public function save(): void
    {
        $schema = $this->getActiveSchema();
        $data = $this->form->getState();
        $flattenedData = collect($data)->dot();

        /*
        * Load environment mapping
        */
        $envMapping = [];
        foreach (SettingsRegistry::getRegisteredPages() as $pageClass) {
            if (method_exists($pageClass, 'env')) {
                $envMapping = array_merge($envMapping, $pageClass::env());
            }
        }   
             
        $envPayload = [];
        foreach ($flattenedData as $key => $value) {
            if (isset($envMapping[$key])) {
                /*
                * This is an env-backed field → write to .env
                */
                $envKey = $envMapping[$key];
                $newValue = $this->normalizeEnvValue($value);
                $currentEnvValue = env($envKey);
                if($this->normalizeEnvValue($currentEnvValue) !== $newValue) {
                    $envPayload[$envKey] = $newValue;
                }
            } else {
                \Branzia\Settings\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            }
        }
        if (!empty($envPayload)) {
            /*dispatch(new EnvironmentSettings($envPayload));*/
            EnvironmentSettings::dispatchSync($envPayload);
        }
        
        /*\Filament\Notifications\Notification::make()->title('Settings saved successfully.')->success()->send();*/
    }
    private function normalizeEnvValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        return trim((string) $value);
    }

    


}
