<div>
    <x-mary-header subtitle="Here you can change mail configuration" >

        <x-slot:title class="text-4xl">
            Mail Settings
        </x-slot:title>

        <x-slot:actions>

        </x-slot:actions>
    </x-mary-header>
    <x-mary-card class="shadow border">
        <form wire:submit.prevent="save">
            <x-mary-input wire:model="request.mail_host"
                          label="Host"
                          class="mb-5"
            />
            <x-mary-input wire:model="request.mail_username"
                          label="Username"
                          class="mb-5"
            />
            <x-mary-input type="password"
                          wire:model="request.mail_password"
                          label="Password"
                          class="mb-5"
            />
            <x-mary-input wire:model="request.mail_address"
                          label="Address"
                          class="mb-5"
            />

            <div class="mb-5">
                <x-mary-select wire:model="request.mail_encryption"
                               label="Encryption"
                               hint="(SSL or TLS)"
                               :options="[
                                [
                                    'value'=>'SSL',
                                    'label'=>'SSL',
                                ],
                                [
                                    'value'=>'TLS',
                                    'label'=>'TLS',
                                ],
                           ]"
                               option-value="value"
                               option-label="label"
                />
            </div>
            <div class="mb-5">
                <x-mary-select wire:model="request.mail_port"
                               label="Port"
                               hint="(465 for SSL or 587 for TLS)"
                               :options="[
                                [
                                    'value'=>'465',
                                    'label'=>'465',
                                ],
                                [
                                    'value'=>'587',
                                    'label'=>'587',
                                ],
                           ]"
                               option-value="value"
                               option-label="label"
                />
            </div>
            <div class="text-center my-3">
                <x-mary-button label="Submit"
                               class="btn-primary"
                               spinner="save"
                               type="submit"
                />
            </div>
        </form>
    </x-mary-card>
</div>
