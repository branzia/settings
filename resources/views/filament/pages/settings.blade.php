<x-filament::page>
    <div class="flex gap-6">
        {{-- Sidebar --}}
        <aside class="w-1/4 border-r pr-4">
            @foreach ($this->getSidebarGroups() as $groupName => $pages)
                <div x-data="{ open: true }" class="mb-4">
                    <button
                        @click="open = !open"
                        class="flex items-center justify-between w-full text-sm font-semibold text-gray-600 uppercase mb-2"
                    >
                        {{ $groupName }}

                        {{-- Chevron Down (when collapsed) --}}
                        <svg x-show="!open" class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 14a.75.75 0 0 1-.53-.22l-4.25-4.25a.75.75 0 0 1 1.06-1.06L10 12.19l3.72-3.72a.75.75 0 0 1 1.06 1.06l-4.25 4.25A.75.75 0 0 1 10 14Z" clip-rule="evenodd" />
                        </svg>

                        {{-- Chevron Up (when expanded) --}}
                        <svg x-show="open" class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 6a.75.75 0 0 1 .53.22l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 7.81l-3.72 3.72a.75.75 0 1 1-1.06-1.06l4.25-4.25A.75.75 0 0 1 10 6Z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <ul class="space-y-1" x-show="open" x-collapse>
                        @foreach ($pages as $pageClass)
                            @php
                                $isActive = $activeSetting === $pageClass;
                            @endphp
                            <li>
                                <button wire:click="$set('activeSetting', @js($pageClass))"
                                        class="block w-full text-left px-3 py-2 rounded-md text-sm {{ $isActive ? 'bg-primary-50 text-primary-600 font-medium' : 'hover:bg-gray-100' }}"
                                >
                                    {{ $pageClass::$navigationLabel }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </aside>

        {{-- Form Area --}}
        <main class="w-3/4">
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="flex justify-end">
                    <x-filament::button type="submit">
                        Save
                    </x-filament::button>
                </div>
                {{ $this->form }}
            </form>
        </main>
    </div>
</x-filament::page>
