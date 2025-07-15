<div>
    <div class="space-y-4">
        @foreach ($pages as $page)
            <button
                wire:click="$set('activeSection', '{{ $page::class }}')"
                class="px-4 py-2 bg-gray-100 rounded-md {{ $activeSection === $page::class ? 'bg-primary-100 font-bold' : '' }}"
            >
                {{ $page::$navigationLabel }}
            </button>
        @endforeach
    </div>

    <div class="mt-6">
        @php
            $formClass = $activeSection;
        @endphp

        @if ($formClass)
            <form wire:submit.prevent="submit">
                <x-filament::form>
                    {{ $this->renderForm($formClass) }}
                </x-filament::form>
            </form>
        @endif
    </div>
</div>
