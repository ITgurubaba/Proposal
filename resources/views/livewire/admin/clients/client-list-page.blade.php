<div>
    <x-mary-header subtitle="Showing the list of clients." >

        <x-slot:title class="text-4xl">
            Clients
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
                           link="{{ route('admin::company.clients.add') }}"
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
                      :per-page-values="$perPageOptions"
        >
            @scope('cell_id', $item)
            <strong>{{ $item->id }}</strong>
            @endscope

            @scope('cell_contact_name', $item)
            {{ $item->contact_name ?? '' }}
            @endscope

            @scope('cell_customer_type', $item)
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-white">
                {{ $this->getCustomerTypeLabel($item->customer_type) }}
            </span>
            @endscope

            @scope('cell_company_registration_number', $item)
            {{ $item->company_registration_number ?? '-' }}
            @endscope

            @scope('cell_city', $item)
            {{ $item->city ?? '-' }}
            @endscope

            @scope('cell_country', $item)
            {{ $item->country ?? '-' }}
            @endscope

            @scope('actions', $item)
            <div class="flex gap-3">
                <div>
                    <x-mary-button icon="o-pencil-square"
                                   class="btn-sm btn-primary btn-circle"
                                   tooltip="Edit"
                                   href="{{ route('admin::company.clients.edit',['client_id'=>$item->id]) }}"
                                   wire:navigate
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
</div>
