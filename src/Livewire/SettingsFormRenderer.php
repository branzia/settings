<?php

namespace Branzia\Settings\Livewire;

use Livewire\Component;
use Branzia\Settings\Services\SettingsRegistry;

class SettingsFormRenderer extends Component
{
    public string $activeSection = '';

    public function mount(): void
    {
        $pages = SettingsRegistry::getRegisteredPages();
        if (!empty($pages)) {
            $this->activeSection = $pages[0];
        } else {
            $this->activeSection = '';
        }
    }

    public function render()
    {
        return view('branzia-settings::livewire.settings-form-renderer', [
            'pages' => SettingsRegistry::getRegisteredPages(),
            'activeSection' => $this->activeSection,
        ]);
    }
}
