<div>
    <x-mary-header subtitle="Showing the list of services.">

        <x-slot:title class="text-4xl">
            Services
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass"
                          placeholder="Search..."
                          type="search"
                          wire:model.debounce.500ms="search"
            />
        </x-slot:middle>

        <x-slot:actions>
            <x-mary-button icon="o-plus"
                           class="btn-primary btn-square"
                           href="{{ route('admin::services:add') }}"
                           wire:navigate
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

            @scope('cell_name', $item)
                {{ $item->name ?? '' }}
            @endscope

            @scope('cell_status', $item)
                <button wire:click="toggleStatus({{ $item->id }})"
                        class="btn btn-sm {{ $item->status ? 'btn-success' : 'btn-error' }}">
                    {{ $item->status ? 'Active' : 'Inactive' }}
                </button>
            @endscope

            @scope('actions', $item)
                <div class="flex gap-3">

                    <x-mary-button icon="o-pencil-square"
                                   class="btn-sm btn-primary btn-circle"
                                   tooltip="Edit"
                                   href="{{ route('admin::services:edit',['service_id'=>$item->id]) }}"
                                   wire:navigate
                    />

                    <div x-data="{
                        confirmDelete(id){
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
                                $wire.destroy(id);
                            }
                          });
                        }
                    }">
                        <x-mary-button icon="o-trash"
                                       tooltip="Delete"
                                       class="btn-sm btn-error text-white btn-circle"
                                       @click="confirmDelete({{ $item->id }})"
                        />
                    </div>

                </div>
            @endscope

        </x-mary-table>
    </x-mary-card>
</div>
