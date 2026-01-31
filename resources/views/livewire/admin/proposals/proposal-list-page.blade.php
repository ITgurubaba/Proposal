<div>
    <x-mary-header subtitle="Manage and track all proposals.">

        <x-slot:title class="text-4xl">
            Proposals
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
                           href="{{ route('admin::proposals:create') }}"
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

            @scope('cell_client_name', $item)
                <div>
                    <div class="font-semibold">{{ $item->client->contact_name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $item->client->company_registration_number ?? '' }}</div>
                </div>
            @endscope

            @scope('cell_total_price', $item)
                <span class="font-semibold text-lg">£{{ number_format($item->total_price, 2) }}</span>
            @endscope

            @scope('cell_status', $item)
                @switch($item->status)
                    @case('draft')
                        <x-mary-badge value="Draft" class="badge-secondary" />
                        @break
                    @case('sent')
                        <x-mary-badge value="Sent" class="badge-primary" />
                        @break
                    @case('accepted')
                        <x-mary-badge value="Accepted" class="badge-success" />
                        @break
                    @case('rejected')
                        <x-mary-badge value="Rejected" class="badge-error" />
                        @break
                @endswitch
            @endscope

            @scope('cell_created_at', $item)
                {{ $item->created_at->format('M d, Y') }}
            @endscope

            @scope('actions', $item)
                <div class="flex gap-3">

                    <x-mary-button icon="o-eye"
                                   class="btn-sm btn-info btn-circle"
                                   tooltip="View"
                                   href="{{ route('admin::proposals:view', ['proposal_id' => $item->id]) }}"
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
