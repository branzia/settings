<x-filament::page>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3 border-r pr-4">
            <h2 class="text-lg font-semibold mb-2">Settings Sections</h2>
            <ul>
                <!-- Dynamic section links via Livewire/Alpine -->
            </ul>
        </div>
        <div class="col-span-9">
            @livewire('branzia-settings::settings-form-renderer')
        </div>
    </div>
</x-filament::page>
