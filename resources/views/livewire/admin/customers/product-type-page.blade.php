<div>
    <x-mary-header subtitle="Showing the list of all product attributes" >

        <x-slot:title class="text-4xl">
            Product Attributes
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass"
                          placeholder="Search..."
                          type="search"
                          wire:model.live="search"
            />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-select wire:model.live="filterType"
                           :options="ProductHelper::$productTypeFilterOptions"
                           option-label="label"
                           option-value="value"
            />
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
                      :per-page-values="[10,15,25,50,100]"
        >
            @scope('cell_id', $item)
            <strong>{{ $item->id }}</strong>
            @endscope

            @scope('cell_type', $item)
            {{ ucfirst($item->type ??'') }}
            @endscope

            @scope('cell_name', $item)
            {{ $item->name ??'' }}
            @endscope

            @scope('cell_color_code', $item)
               @if($item->type == "color")
                <div class="h-4 w-4 rounded-full" style="background-color: {{ $item->color_code ??'' }}"></div>
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
                                   wire:key="edit_btn_{{$item->id}}"
                                   wire:click.prevent="openAddEditModal({{ $item->id }})"
                                   spinner="openAddEditModal({{ $item->id }})"
                                   wire:loading.attr="disabled"
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

    <form wire:submit.prevent="createOrUpdate">
        <x-mary-drawer title="{{ Arr::has($request,'id')?'Edit':'New' }} Product Attribute"
                       subtitle="Here you can edit details of attribute"
                       wire:model="editModal"
                       class="backdrop-blur"
                       with-close-button
                       right
                       separator
                       close-on-escape
                       class="w-11/12 lg:w-1/3"
        >
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <x-mary-select label="Type"
                                   :options="\App\Models\ProductType::PRODUCT_TYPES"
                                   option-label="label"
                                   option-value="value"
                                   wire:model.live="request.type"
                    />
                </div>
                <div class="col-span-12 {{ $request['type'] == \App\Models\ProductType::TYPE_COLOR?'':'hidden' }}">
                    <x-mary-colorpicker wire:model="request.color_code"
                                        label="Select Color"
                                        icon="o-swatch"
                    />
                </div>
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
                    <x-mary-input label="Position"
                                  wire:model="request.position"
                                  type="number"
                                  min="0"
                                  required
                    />
                </div>
                <div class="col-span-12">
                    <x-mary-select wire:model="request.status"
                                   label="Status"
                                   :options="BackendHelper::STATUS_OPTIONS"
                                   option-value="value"
                                   option-label="label"
                    />
                </div>
            </div>
            <x-slot:actions>
                <x-mary-button label="Save"
                               class="btn-primary"
                               icon="o-check"
                               spinner="createOrUpdate"
                               type="submit"
                />
                <x-mary-button label="Cancel" type="button" @click="$wire.editModal = false" />
            </x-slot:actions>
        </x-mary-drawer>
    </form>
</div>
