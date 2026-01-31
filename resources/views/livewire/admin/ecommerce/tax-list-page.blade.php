<div>
    <x-mary-header subtitle="Showing the list of all Taxes." >

        <x-slot:title class="text-4xl">
            Vat Taxes
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass"
                          placeholder="Search..."
                          type="search"
                          wire:model.live="search"
            />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-plus"
                           class="btn-primary btn-square"
                           wire:click.prevent="openAddEditModal"
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
                      :per-page-values="$perPageOptions"
        >
            @scope('cell_id', $item)
            <strong>{{ $item->id }}</strong>
            @endscope

            @scope('cell_name', $item)
            {{ $item->name ??'' }}
            @endscope

            @scope('cell_rate', $item)
               @if($item->type == "percentage")
                {{ $item->rate ??'' }} %
                @else
                {{ currency($item->rate ??'') }}
               @endif
            @endscope

            @scope('cell_status', $item)
            @if($item->status)
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
                                   wire:click.prevent="openAddEditModal({{ $item->id }})"
                                   spinner="openAddEditModal({{ $item->id }})"
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

    <form wire:submit.prevent="{{ Arr::has($request,'id')?'Save':'Submit' }}">
        <x-mary-modal wire:model="editModal"
                      title="{{ Arr::has($request,'id')?'Edit':'New' }} Vat Tax"
                      subtitle="Here you can add or edit tax details"
                      class="overflow-auto"
        >
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    <x-mary-input wire:model="request.name"
                                  label="Name"
                    />
                </div>
                <div class="col-span-12">
                    <x-mary-textarea wire:model="request.description"
                                     label="Description"
                    />
                </div>
                <div class="col-span-12">
                    <x-mary-select wire:model.live="request.type"
                                   label="Tax Type"
                                   :options="Ecommerce::getTypes('tax')"
                                   option-label="label"
                                   option-value="value"
                    />
                </div>
                <div class="col-span-12">
                    @if($request['type'] == "percentage")
                        <x-mary-input wire:model="request.rate"
                                      label="Rate"
                                      type="number"
                                      min="0"
                                      step="any"
                                      suffix="%"
                        />
                    @else
                        <x-mary-input wire:model="request.rate"
                                      label="Rate"
                                      type="number"
                                      min="0"
                                      step="any"
                                      suffix="{{ getDefaultCurrencySymbol() }}"
                        />
                    @endif
                </div>
                <div class="col-span-12">
                    <x-mary-select wire:model="request.status"
                                   label="Status"
                                   :options="BackendHelper::STATUS_OPTIONS"
                                   option-label="label"
                                   option-value="value"
                    />
                </div>
            </div>

            <x-slot:actions>
                <div class="flex gap-3">
                    <x-mary-button label="Submit"
                                   class="btn-primary"
                                   type="submit"
                    />
                    <x-mary-button  label="Close"
                                    class="btn-light"
                                    @click.prevent="$wire.editModal = false"
                    />
                </div>
            </x-slot:actions>
        </x-mary-modal>
    </form>

</div>
