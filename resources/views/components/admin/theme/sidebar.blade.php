<div>
    <x-slot:sidebar drawer="main-drawer"
                    collapsible
                    class="bg-base-200 lg:bg-inherit"
    >

        <div class="hidden lg:block">
            <div class="pt-6 px-6 pb-3">

                {{-- Brand --}}


                <div class="flex flex-wrap gap-5"

                >
                    <img src="{{ asset(config('settings.admin_logo','assets/default/logo/flow.png')) }}"
                         x-bind:class="collapsed?'object-contain':''"
                         class="h-12"
                    />
                    <div class="pt-1 text-lg font-bold"
                         x-show="!collapsed"
                    >

                    </div>
                </div>
            </div>
            <x-mary-menu-separator />
        </div>

        {{-- User --}}
        @auth
            <x-mary-list-item :item="auth()->user()"
                              value="name"
                              sub-value="email"
                              no-separator
                              no-hover
            >
                <x-slot:avatar>
                    <x-mary-avatar :image="auth()->user()->avatarUrl()" class="!w-12" />
                </x-slot:avatar>
                <x-slot:actions>
                    <x-mary-dropdown>
                        <x-slot:trigger>
                            <x-mary-button icon="s-cog-6-tooth" class="btn-circle btn-sm" />
                        </x-slot:trigger>
                        <x-mary-menu-item icon="s-user-circle"
                                          title="Profile"
                                          link="{{ route('admin::profile') }}"
                        />

                        <x-mary-menu-item title="Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />

                        <x-mary-menu-item icon="o-power"
                                          no-wire-navigate
                                          link="{{ route('admin::logout') }}"
                                          tooltip-left="logoff"
                                          title="Logout"
                        />
                    </x-mary-dropdown>
                </x-slot:actions>
            </x-mary-list-item>

            <x-mary-menu-separator />
        @endauth

        {{-- Activates the menu item when a route matches the `link` property --}}

        <x-mary-menu activate-by-route >
            @foreach($sidebarMenu as $menu)
                @if(isset($menu['subMenu']) && count($menu['subMenu'])>0)
                    <x-mary-menu-sub title="{{ $menu['name'] ??'' }}"
                                     icon="{{ $menu['icon'] ??'' }}"
                    >
                        @foreach($menu['subMenu'] as $subMenu)
                            @if(isset($subMenu['subMenu']) && count($subMenu['subMenu'])>0)
                                <x-mary-menu-sub title="{{ $subMenu['name'] ??'' }}"
                                                 icon="{{ $subMenu['icon'] ??'' }}"
                                >
                                    @foreach($subMenu['subMenu'] as $supMenu)
                                        <x-mary-menu-item title="{{ $supMenu['name'] ??'' }}"
                                                          icon="{{ $supMenu['icon'] ??'' }}"
                                                          link="{{ $supMenu['link'] }}"
                                                          :no-wire-navigate="$supMenu['no-navigate'] ??false"

                                        />
                                    @endforeach
                                </x-mary-menu-sub>
                            @else
                                <x-mary-menu-item title="{{ $subMenu['name'] ??'' }}"
                                                  icon="{{ $subMenu['icon'] ??'' }}"
                                                  link="{{ $subMenu['link'] }}"
                                                  :no-wire-navigate="$subMenu['no-navigate'] ??false"
                                />
                            @endif
                        @endforeach
                    </x-mary-menu-sub>
                @else
                    <x-mary-menu-item title="{{ $menu['name'] ??'' }}"
                                      icon="{{ $menu['icon'] ??'' }}"
                                      link="{{ $menu['link'] }}"
                                      :no-wire-navigate="$menu['no-navigate'] ??false"
                    />
                @endif
            @endforeach

        </x-mary-menu>

    </x-slot:sidebar>
</div>
