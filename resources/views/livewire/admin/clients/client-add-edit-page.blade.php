<div>
    <x-mary-header subtitle="Here you can add or edit the details of client.">

        <x-slot:title class="text-4xl">
            {{ checkData($client_id) ? 'Edit' : 'New' }} Client
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left"
                class="btn-light btn-sm btn-circle"
                href="{{ route('admin::company.clients.list') }}"
                wire:navigate
                tooltip="Back to list" />
        </x-slot:actions>
    </x-mary-header>

    {{-- ✅ FIX: static submit method --}}
    <form wire:submit.prevent="submitForm">
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 lg:col-span-9">
                <x-mary-card class="shadow border mb-5">

                    {{-- ✅ FIX: preserve active tab --}}
                    <x-mary-tabs wire:model="activeTab">

                        {{-- CONTACT TAB --}}
                        <x-mary-tab name="contact" icon="o-user" label="Contact Details">
                            <div>
                                <div class="mb-5">
                                    <x-mary-select
                                        wire:model.defer="request.customer_type"
                                        label="Select the type of the customer"
                                        :options="$this->customerTypes"
                                        option-label="label"
                                        option-value="value"
                                        placeholder="Select customer type"
                                        required
                                    />
                                </div>

                                <div class="mb-5">
                                    <x-mary-input
                                        wire:model.defer="request.company_registration_number"
                                        label="Company House Registration Number"
                                        placeholder="Enter company registration number"
                                    />
                                </div>

                                <div class="mb-5">
                                    <x-mary-input
                                        wire:model.defer="request.contact_name"
                                        label="Contact Name"
                                        placeholder="Enter contact name"
                                        required
                                    />
                                </div>
                            </div>
                        </x-mary-tab>

                        {{-- PERSONS TAB --}}
                        <x-mary-tab name="persons" icon="o-users" label="Primary Persons">
                            <div>
                                @foreach ($persons as $index => $person)
                                    <x-mary-card
                                        class="border border-dashed relative mb-5"
                                        wire:key="person-{{ $person['id'] }}"
                                    >
                                        <div class="absolute top-0 right-0">
                                            @if (count($persons) > 1)
                                                <x-mary-button
                                                    icon="o-trash"
                                                    class="btn-error btn-sm btn-circle text-white"
                                                    wire:click.prevent="removePerson({{ $index }})"
                                                    wire:loading.attr="disabled"
                                                />
                                            @endif
                                        </div>

                                        <div class="card-header pb-2">
                                            <span class="card-title">Primary Person {{ $index + 1 }}</span>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-6">
                                                <x-mary-input
                                                    wire:model.defer="persons.{{ $index }}.first_name"
                                                    label="Name"
                                                    placeholder="First name"
                                                />
                                            </div>

                                            <div class="col-span-6">
                                                <x-mary-input
                                                    wire:model.defer="persons.{{ $index }}.last_name"
                                                    label="Last Name"
                                                    placeholder="Last name"
                                                />
                                            </div>

                                            <div class="col-span-6">
                                                <x-mary-input
                                                    wire:model.defer="persons.{{ $index }}.email"
                                                    label="Email"
                                                    type="email"
                                                    placeholder="Email address"
                                                />
                                            </div>

                                            <div class="col-span-6">
                                                <x-mary-input
                                                    wire:model.defer="persons.{{ $index }}.phone"
                                                    label="Phone"
                                                    placeholder="Phone number"
                                                />
                                            </div>
                                        </div>
                                    </x-mary-card>
                                @endforeach

                                <div class="col-span-12 mt-3">
                                    <div class="flex justify-end">
                                        <x-mary-button
                                            class="btn-success text-white"
                                            icon="o-plus"
                                            label="Add Primary Person"
                                            wire:click.prevent="addNewPerson"
                                            spinner="addNewPerson"
                                        />
                                    </div>
                                </div>
                            </div>
                        </x-mary-tab>

                        {{-- ADDRESS TAB --}}
                        <x-mary-tab name="address" icon="o-map-pin" label="Address">
                            <div>
                                <div class="mb-5">
                                    <x-mary-input
                                        wire:model.defer="request.address_line_1"
                                        label="Address Line 1"
                                        placeholder="Street address"
                                    />
                                </div>

                                <div class="mb-5">
                                    <x-mary-input
                                        wire:model.defer="request.address_line_2"
                                        label="Address Line 2"
                                        placeholder="Apartment, suite, unit, building, floor, etc."
                                    />
                                </div>

                                <div class="mb-5">
                                    <x-mary-input
                                        wire:model.defer="request.address_line_3"
                                        label="Address Line 3"
                                        placeholder="Additional address information"
                                    />
                                </div>

                                <div class="grid grid-cols-12 gap-4 mb-5">
                                    <div class="col-span-6">
                                        <x-mary-input
                                            wire:model.defer="request.city"
                                            label="City"
                                            placeholder="City name"
                                        />
                                    </div>

                                    <div class="col-span-6">
                                        <x-mary-input
                                            wire:model.defer="request.zip_code"
                                            label="Zip Code"
                                            placeholder="Postal/Zip code"
                                        />
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <x-mary-select
                                        wire:model.defer="request.country"
                                        label="Country"
                                        :options="$countries"
                                        option-label="label"
                                        option-value="value"
                                        placeholder="Select country"
                                        searchable
                                    />
                                </div>
                            </div>
                        </x-mary-tab>

                    </x-mary-tabs>
                </x-mary-card>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-span-12 lg:col-span-3 relative">
                <div class="sticky top-0 left-0">
                    <x-mary-card class="shadow border">
                        <div class="mb-5">
                            <x-mary-button
                                label="{{ checkData($client_id) ? 'Update Client' : 'Save Client' }}"
                                class="btn-primary"
                                spinner="{{ checkData($client_id) ? 'Save' : 'Submit' }}"
                                type="submit"
                            />
                        </div>
                    </x-mary-card>
                </div>
            </div>
        </div>
    </form>
</div>
