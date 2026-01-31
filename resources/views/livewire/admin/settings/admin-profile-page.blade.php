<div>
    <x-mary-header subtitle="Here you can edit your profile" >

        <x-slot:title class="text-4xl">
            Profile
        </x-slot:title>

        <x-slot:actions>

        </x-slot:actions>
    </x-mary-header>

    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6 lg:col-span-2">
            @php
              $avatarUrl = asset($request['image'] ??'assets/default/user.png');
            @endphp
            <x-mary-card class="border shadow">
                <div class="relative">
                    <div class="absolute top-0 right-0 hidden lg:block">
                        <x-mary-badge class="bg-primary text-white" value="{{ $user->userType() }}" />
                    </div>
                    <x-mary-avatar :image="$avatarUrl"
                                   class="!w-24"
                    >

                        <x-slot:title class="text-3xl pl-2">
                            {{ $request['name']." ".$request['last_name'] }}
                        </x-slot:title>

                        <x-slot:subtitle class="text-gray-500 flex flex-col gap-1 mt-2 pl-2">
                            <x-mary-icon name="o-envelope" label="{{ $request['email'] ??'--' }}" />
                            <x-mary-icon name="o-phone" label="{{ $request['phone'] ??'--' }}" />
                        </x-slot:subtitle>

                    </x-mary-avatar>
                </div>
                <div class="mt-5 text-center">
                    <x-mary-button label="Change Photo"
                                   type="button"
                                   class="btn btn-sm btn-light"
                                   @click="$wire.photoModal = true"
                    />
                </div>
            </x-mary-card>
        </div>
        <div class="col-span-6 lg:col-span-4">
            <x-mary-card class="border shadow">
                <x-mary-tabs wire:model="selectedTab">
                    <x-mary-tab name="profile" label="Profile" icon="o-user">
                        <form wire:submit.prevent="saveProfile">
                            <div class="grid lg:grid-cols-2 gap-3">
                                <x-mary-input label="First Name"
                                              wire:model="request.name"
                                />
                                <x-mary-input label="Last Name"
                                              wire:model="request.name"
                                />
                                <x-mary-input wire:model="request.email"
                                              label="Email"
                                />
                                <x-forms.input-label label="Phone">
                                    <x-admin.forms.phone-input wire:model="request.phone"

                                    />
                                </x-forms.input-label>
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
                            </div>
                            <div class="mt-3 text-center">
                                <x-mary-button label="Save Profile"
                                               class="btn btn-primary"
                                               spinner="saveProfile"
                                               type="submit"
                                />
                            </div>
                        </form>
                    </x-mary-tab>
                    <x-mary-tab name="security" label="Change Password" icon="o-lock-closed">
                       <form wire:submit.prevent="changePassword">
                           <div class="grid gap-3">
                               <div x-data="{
                                        type: 'password',
                                        togglePassword: function() {
                                             this.type = (this.type === 'password' ? 'text' : 'password');
                                        }
                                    }"
                               >
                                   <x-mary-input wire:model="passwordRequest.old_password"
                                                 label="Old Password"
                                                 x-bind:type="type"

                                   >
                                       <x-slot name="suffix" class="bg-transparent border-none">
                                           <a href="javascript:void(0)" @click.prevent="togglePassword">
                                               <x-mary-icon name="o-eye" x-show="type == 'password'" />
                                               <x-mary-icon name="o-eye-slash" x-show="type == 'text'" />
                                           </a>
                                       </x-slot>
                                   </x-mary-input>
                               </div>
                               <div x-data="{
                                        type: 'password',
                                        togglePassword: function() {
                                             this.type = (this.type === 'password' ? 'text' : 'password');
                                        }
                                    }"
                               >
                                   <x-mary-input wire:model="passwordRequest.password"
                                                 label="New Password"
                                                 x-bind:type="type"

                                   >
                                       <x-slot name="suffix" class="bg-transparent border-none">
                                           <a href="javascript:void(0)" @click.prevent="togglePassword">
                                               <x-mary-icon name="o-eye" x-show="type == 'password'" />
                                               <x-mary-icon name="o-eye-slash" x-show="type == 'text'" />
                                           </a>
                                       </x-slot>
                                   </x-mary-input>
                               </div>
                               <x-mary-input wire:model="passwordRequest.password_confirmation"
                                             label="Retype Password"
                                             type="text"
                               />
                           </div>
                           <div class="mt-3 text-center">
                               <x-mary-button label="Change Password"
                                              class="btn btn-primary"
                                              spinner="changePassword"
                                              type="submit"
                               />
                           </div>
                       </form>
                    </x-mary-tab>
                </x-mary-tabs>

            </x-mary-card>
        </div>
    </div>

    <x-mary-modal wire:model="photoModal" class="backdrop-blur">
        <div class="mb-5">
            <x-forms.input-label label="Avatar Image"
                                 description="(Dimension:500 x 500 pixels)"
            >
                <x-forms.filepond  wire:model="request.image"
                                   folder="images/"
                                   accept="image/*"
                />
            </x-forms.input-label>
        </div>
        <x-slot:actions>
            <x-mary-button label="Cancel"
                           @click="$wire.photoModal = false"
                           type="button"
            />
            <x-mary-button label="Save"
                           class="btn btn-primary"
                           type="submit"
                           spinner="saveAvatar"
                           @click="$wire.saveAvatar()"
            />
        </x-slot:actions>
    </x-mary-modal>

</div>
