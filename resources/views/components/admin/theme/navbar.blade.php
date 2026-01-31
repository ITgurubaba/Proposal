<div>
    <x-mary-nav sticky full-width  class="bg-base-200 shadow dark:border-b-gray-700 lg:bg-inherit" id="navbar">
        <label for="main-drawer" class="lg:hidden mr-3">
            <x-mary-icon name="o-bars-3" class="cursor-pointer" />
        </label>
        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-mary-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Brand --}}
            <div class="flex flex-wrap gap-5 lg:hidden">
                <img src="{{ asset('assets/default/logo/flow.png') }}"
                     style="height: 35px;"
                />

                <div class="pt-1 text-lg font-bold hidden lg:block">
                    {{ config('settings.site_name') ??getenv('APP_NAME') }}
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="flex flex-wrap gap-3">
                    <x-mary-button icon="o-home"
                                   responsive="true"
                                   class="btn-ghost btn-sm btn-circle"
                                   tooltip="Home Page"
                                   link="{{ url('/') }}"
                                   target="_blank"
                                   no-wire-navigate
                    />
                    <livewire:admin.components.boost-up-button />
                </div>
            </div>
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>
            <div class="hidden sm:block">

            </div>
            <x-mary-button icon="o-envelope"  class="btn-ghost btn-sm btn-circle" responsive="true" />

            <x-mary-button icon="o-bell"
                           class="btn-ghost btn-sm btn-circle"
                           responsive="true"
                           onclick="dispatchEventCall('open-notification-drawer')"
            />
            <x-mary-theme-toggle class="btn btn-sm btn-circle btn-ghost" responsive="true" />
        </x-slot:actions>
    </x-mary-nav>
</div>
