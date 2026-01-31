@php
$roles = [
   ['key'=>'admin','value'=>'Admin'],
   ['key'=>'sub_admin','value'=>'SubAdmin'],
];
@endphp

<div>
    <x-mary-header subtitle="Here you can add or edit the details of team user." >

        <x-slot:title class="text-4xl">
            {{ checkData($final_id)?'Edit':'New' }} Team User
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left"
                           class="btn-light btn-sm btn-circle"
                           x-on:click=""
                           href="{{ back()->getTargetUrl() }}"
                           wire:navigate
                           tooltip="Back to list"
            />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card class="shadow border">
        <form wire:submit.prevent="{{ checkData($final_id)?'Save':'Submit' }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                <div class="col-span-2">
                    <div class="lg:flex gap-4 align-middle items-center">
                        <div class="lg:w-2/5">
                            <x-forms.input-label label="Avatar Image"
                                                 description="(Dimension:500 x 500 pixels)"
                            >
                                <x-forms.filepond  wire:model="request.image"
                                                   folder="images/"
                                                   accept="image/*"
                                />
                            </x-forms.input-label>
                        </div>
                        <div>
                            <x-admin.forms.image-viewer wire:model="request.image"
                                                        class="h-16 w-16 rounded-full shadow-sm"
                                                        default-image="assets/default/user.png"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <x-mary-select wire:model="request.user_type"
                                   label="Member Role"
                                   :options="$roles"
                                   option-value="key"
                                   option-label="value"
                    >
                        <option value="admin">Admin</option>
                        <option value="sub_admin">Sub-Admin</option>
                    </x-mary-select>
                </div>

                <x-mary-input wire:model="request.name"
                              label="First Name"
                />

                <x-mary-input wire:model="request.last_name"
                              label="Last Name"
                />
                <x-mary-input wire:model="request.email"
                              label="Email"
                />
                <x-forms.input-label label="Phone">
                    <x-admin.forms.phone-input wire:model="request.phone" />
                </x-forms.input-label>
                <div x-data="{
                                type: 'password',
                                togglePassword: function() {
                                     this.type = (this.type === 'password' ? 'text' : 'password');
                                }
                            }"
                >
                    <x-mary-input wire:model="request.password"
                                  label="Password"
                                  x-bind:type="type"
                                  hint="If user already registered and then you can leave this field if don't want to change password"

                    >
                        <x-slot name="suffix" class="bg-transparent border-none">
                            <a href="javascript:void(0)" @click.prevent="togglePassword">
                                <x-mary-icon name="o-eye" x-show="type == 'password'" />
                                <x-mary-icon name="o-eye-slash" x-show="type == 'text'" />
                            </a>
                        </x-slot>
                    </x-mary-input>
                </div>

                <x-mary-input wire:model="request.password_confirmation"
                              label="Retype password"
                />
                <div class="col-span-1">
                    <div class="choice-list">
                        <x-mary-choices
                            label="Country"
                            wire:model="request.country"
                            option-value="id"
                            option-label="nicename"
                            :options="$countries"
                            single
                            searchable
                            search-function="searchCountry"
                        />
                    </div>
                </div>
                <x-mary-input wire:model="request.state"
                              label="State"
                />
                <x-mary-input wire:model="request.city"
                              label="City"
                />
                <x-mary-input wire:model="request.zipcode"
                              label="Zipcode"
                />
                <div class="col-span-2">
                    <x-mary-textarea wire:model="request.address"
                                     label="Address"
                    />
                </div>
                <div class="col-span-2">
                    <div class="flex">
                        <x-mary-toggle wire:model="request.status"
                                       label="Status"
                                       right
                                       value="1"
                                       :checked="(bool)$request['status']"
                        />
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <x-mary-button label="Save Member"
                               class="btn-primary"
                               spinner="{{ checkData($final_id)?'Save':'Submit' }}"
                               type="submit"
                />
            </div>
        </form>
    </x-mary-card>
</div>
