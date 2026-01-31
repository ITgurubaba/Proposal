<div>
    <x-mary-header subtitle="Create and manage services">
        <x-slot:title class="text-4xl">
            Service Manager
        </x-slot:title>
    </x-mary-header>

    {{-- ================= SERVICE FORM ================= --}}
    <x-mary-card class="shadow border mb-6">
        <form wire:submit.prevent="saveService" class="grid grid-cols-3 gap-4">

            <x-mary-input label="Service Name" wire:model="name" placeholder="VAT, Payroll, Company Formation" />

            <x-mary-input label="Base Price" type="number" wire:model="base_price" />

            <x-mary-select
                label="Pricing Type"
                wire:model="pricing_type"
                :options="[
                    ['id' => 'individual', 'name' => 'Individual'],
                    ['id' => 'bulk', 'name' => 'Bulk']
                ]"
                option-label="name"
                option-value="id" />

            <div class="col-span-3">
                <x-mary-button type="submit" class="btn-primary">
                    Save Service
                </x-mary-button>
            </div>
        </form>
    </x-mary-card>

    {{-- ================= SERVICES LIST ================= --}}
    @foreach ($services as $service)
        <x-mary-card class="shadow border mb-4">

            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-bold">
                    {{ $service->name }}
                    @if ($service->pricing_type === 'bulk')
                        (Package £{{ $service->base_price }})
                    @else
                        (Individual Pricing)
                    @endif
                </h2>

                <x-mary-button
                    icon="o-trash"
                    class="btn-error btn-sm"
                    wire:click="deleteService({{ $service->id }})" />
            </div>

            {{-- ================= SUB SERVICES ================= --}}
            <h3 class="font-semibold mb-2">Sub Services</h3>

            <table class="table w-full mb-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price !== null ? '£'.$item->price : 'Included' }}</td>
                            <td>
                                <x-mary-button
                                    icon="o-pencil"
                                    class="btn-warning btn-xs"
                                    wire:click="editItem({{ $item->id }})" />

                                <x-mary-button
                                    icon="o-trash"
                                    class="btn-error btn-xs"
                                    wire:click="deleteItem({{ $item->id }})" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- SUB SERVICE FORM --}}
            <form
                wire:submit.prevent="{{ $editItemId ? 'updateItem('.$service->id.')' : 'saveItem('.$service->id.')' }}"
                class="grid grid-cols-3 gap-3 mb-6">

                <x-mary-input label="Sub Service Name" wire:model="item_name" placeholder="VAT Registration" />

                <x-mary-input label="Price" type="number" wire:model="item_price" placeholder="50" />

                <div class="flex items-end">
                    <x-mary-button type="submit" class="btn-secondary">
                        {{ $editItemId ? 'Update Sub Service' : 'Add Sub Service' }}
                    </x-mary-button>
                </div>
            </form>

            {{-- ================= SERVICE FIELDS ================= --}}
            <h3 class="font-semibold mb-2">Service Fields</h3>

            <table class="table w-full mb-4">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service->fields as $field)
                        <tr>
                            <td>{{ $field->field_label }}</td>
                            <td>{{ $field->field_type }}</td>
                            <td>{{ $field->is_required ? 'Yes' : 'No' }}</td>
                            <td>
                                <x-mary-button
                                    icon="o-pencil"
                                    class="btn-warning btn-xs"
                                    wire:click="editField({{ $field->id }})" />

                                <x-mary-button
                                    icon="o-trash"
                                    class="btn-error btn-xs"
                                    wire:click="deleteField({{ $field->id }})" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- SERVICE FIELD FORM --}}
            <form
                wire:submit.prevent="{{ $editFieldId ? 'updateField('.$service->id.')' : 'saveField('.$service->id.')' }}"
                class="grid grid-cols-4 gap-3">

                <x-mary-input label="Field Name" wire:model="field_name" placeholder="vat_scheme" />

                <x-mary-input label="Field Label" wire:model="field_label" placeholder="VAT Scheme" />

                <x-mary-select
                    label="Field Type"
                    wire:model.live="field_type"
                    :options="[
                        ['id' => 'text', 'name' => 'Text'],
                        ['id' => 'number', 'name' => 'Number'],
                        ['id' => 'select', 'name' => 'Select'],
                        ['id' => 'date', 'name' => 'Date'],
                    ]"
                    option-label="name"
                    option-value="id" />

                <x-mary-checkbox label="Required" wire:model="is_required" />

                @if ($field_type === 'select')
                    <div class="col-span-4">
                        <x-mary-input
                            label="Options (comma separated)"
                            wire:model="options"
                            placeholder="Monthly, Quarterly, Yearly" />
                    </div>
                @endif

                <div class="col-span-4">
                    <x-mary-button type="submit" class="btn-secondary">
                        {{ $editFieldId ? 'Update Field' : 'Add Field' }}
                    </x-mary-button>
                </div>
            </form>

        </x-mary-card>
    @endforeach
</div>
