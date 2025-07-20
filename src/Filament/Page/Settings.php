<?php

namespace Branzia\Settings\Filament\Page;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Illuminate\Support\Collection;
use Branzia\Settings\Services\SettingsRegistry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
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
        $settings = \Branzia\Settings\Models\Setting::pluck('value', 'key')->map(function ($value) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $value;
        })->toArray();
        $this->formData = \Illuminate\Support\Arr::undot($settings);
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
        foreach ($flattenedData as $key => $value) {
            \Branzia\Settings\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        \Filament\Notifications\Notification::make()->title('Settings saved successfully.')->success()->send();
    }

    


}
