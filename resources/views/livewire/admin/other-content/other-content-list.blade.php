<div>
    <x-mary-header subtitle="Manage static website content like Privacy Policy, Laws, Engagement Letter etc.">

        <x-slot:title class="text-4xl">
            Other Content
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input
                icon="o-magnifying-glass"
                placeholder="Search..."
                type="search"
                wire:model.debounce.500ms="search"
            />
        </x-slot:middle>

        <x-slot:actions>
            <x-mary-button
                icon="o-plus"
                class="btn-primary btn-square"
                href="{{ route('admin::other-content:add') }}"
                wire:navigate
            />
        </x-slot:actions>

    </x-mary-header>

    <x-mary-card class="shadow border">

        <x-mary-table :headers="$headers" :rows="$contents" show-empty-text>

            @scope('cell_id', $item)
                <strong>{{ $item->id }}</strong>
            @endscope

            @scope('cell_title', $item)
                <div class="font-semibold">
                    {{ $item->title }}
                </div>
            @endscope

            @scope('cell_status', $item)
                @if($item->status)
                    <span class="btn btn-sm btn-success">Active</span>
                @else
                    <span class="btn btn-sm btn-error">Inactive</span>
                @endif
            @endscope

            @scope('cell_created_at', $item)
                {{ $item->created_at->format('M d, Y') }}
            @endscope

            @scope('actions', $item)
                <div class="flex gap-3">

                    <x-mary-button
                        icon="o-pencil"
                        class="btn-sm btn-warning btn-circle"
                        tooltip="Edit"
                        href="{{ route('admin::other-content:edit',$item->id) }}"
                        wire:navigate
                    />

                    <div x-data="{
                        confirmDelete(id) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'This content will be permanently deleted!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.delete(id);
                                }
                            });
                        }
                    }">
                        <x-mary-button
                            icon="o-trash"
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
