<div>
    <x-mary-header subtitle="Showing the list of currencies." >

        <x-slot:title class="text-4xl">
            Currencies
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass"
                          placeholder="Search..."
                          type="search"
                          wire:model.live="search"
            />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-arrow-path"
                           class="btn-success text-white"
                           label="Sync Exchange Rates"
                           wire:click.prevent="SyncCurrencyRates"
                           spinner="SyncCurrencyRates"
            />
            <x-mary-button icon="o-plus"
                           class="btn-primary btn-square"
                           wire:click.prevent="OpenAddEditModal"
                           wire:loading.attr="disabled"
            />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card class="shadow border">
        <x-mary-table :headers="$headers"
                      :rows="$data"
                      :sort-by="$sortBy"
                      with-pagination
                      show-empty-text
                      per-page="perPage"
                      :per-page-values="[10,15,25,50,100]"
        >
            @scope('cell_id', $item)
            <strong>{{ $item->id }}</strong>
            @endscope


            @scope('cell_active', $item)
            @if($item->active)
                <x-mary-badge value="Active" class="badge-primary" />
            @else
                <x-mary-badge value="Inactive" class="badge-error" />
            @endif
            @endscope

            @scope('actions', $item)
            <div class="flex gap-3">
                <div>
                    <x-mary-button icon="o-pencil-square"
                                   class="btn-sm btn-primary btn-circle"
                                   tooltip="Edit"
                                   wire:click.prevent="OpenAddEditModal({{ $item->id }})"
                                   spinner="OpenAddEditModal({{ $item->id }})"
                    />
                </div>
                <div x-data="{
                             confirmDelete:function(userId){
                               Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'Once deleted, you will not be able to recover this record!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, delete it!',
                                    customClass: {
                                        confirmButton: 'btn btn-primary me-3',
                                        cancelButton: 'btn btn-label-secondary'
                                    },
                                    buttonsStyling: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $wire.destroy(userId);
                                    }
                                });
                             }
                           }"
                >
                    <x-mary-button icon="o-trash"
                                   spinner="destroy('{{ $item->id}}')"
                                   tooltip="Delete"
                                   class="btn-sm btn-error text-white btn-circle"
                                   @click="confirmDelete('{{ $item->id }}')"
                    />
                </div>
            </div>
            @endscope
        </x-mary-table>
    </x-mary-card>


    <x-mary-modal wire:model="editModal"
                  title="{{ Arr::has($request,'id')?'Edit':'New' }} Currency"
                  subtitle="Here you can edit details of currency"
    >
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 mb-3">
                <x-mary-input wire:model="request.name" label="Name" />
            </div>
            <div class="col-span-12 mb-3">
                <x-mary-input wire:model="request.code" label="Code" />
            </div>

            <div class="col-span-12 mb-3">
                <x-mary-input wire:model="request.symbol" label="Symbol" />
            </div>

            <div class="col-span-12 mb-3">
                <x-mary-input wire:model="request.format" label="Format" />
            </div>

            <div class="col-span-12 mb-3">
                <x-mary-input wire:model="request.exchange_rate" label="Exchange Rate" />
            </div>
            <div class="col-span-12">
                <x-mary-select wire:model="request.active"
                               label="Status"
                               :options="BackendHelper::STATUS_OPTIONS"
                               option-value="value"
                               option-label="label"
                />
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Submit"
                           class="btn-primary"
                           spinner="save"
                           wire:click.prevent="save"
            />
            <x-mary-button label="Close"
                           class="btn-light"
                           @click.prevent="$wire.editModal = false"
            />
        </x-slot:actions>
    </x-mary-modal>

</div>
