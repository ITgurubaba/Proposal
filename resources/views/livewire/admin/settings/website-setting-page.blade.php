@php
    $timezones = \App\Models\Country::select(['timezone','nicename','status'])->where('status',1)->get()->toArray();
@endphp

<div>
    <x-mary-header subtitle="Here you can change platform settings" >

        <x-slot:title class="text-4xl">
            Website Settings
        </x-slot:title>

        <x-slot:actions>

        </x-slot:actions>
    </x-mary-header>
    <x-mary-card class="shadow border">
        <form wire:submit.prevent="save">
            <x-mary-select wire:model="request.app_env"
                           label="APP ENV"
                           :options="[ [ 'value'=>'local','label'=>'Local' ],[ 'value'=>'production','label'=>'Production' ], ]"
                           option-label="label"
                           option-value="value"
                           class="mb-5"
            />
            <x-mary-input wire:model="request.app_url"
                          label="APP URL"
                          class="mb-5"
            />
            <x-mary-select wire:model="request.app_timezone"
                           label="APP TIMEZONE"
                           :options="$timezones"
                           option-label="timezone"
                           option-value="timezone"
                           class="mb-5"
            />
            <div class="mb-5">
                <x-forms.input-label label="APP DEBUGBAR" >
                    <x-mary-toggle wire:model="request.app_debug"
                                   :checked="(bool)$request['app_debug']"
                                   value="1"
                    />
                </x-forms.input-label>
            </div>

           <div class="mb-5">
               <x-forms.input-label label="HTTPS REDIRECT" >
                   <x-mary-toggle wire:model="request.https_redirect"
                                  :checked="(bool)$request['https_redirect']"
                                  value="1"
                   />
               </x-forms.input-label>
           </div>


            <div class="text-center">
                <x-mary-button label="Submit"
                               class="btn-primary"
                               spinner="save"
                               type="submit"
                />
            </div>
        </form>
    </x-mary-card>
</div>
