<div>
    <x-mary-header subtitle="Showing the list of contact mails.">

        <x-slot:title class="text-4xl">
            Contact Mail
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass" placeholder="Search..." type="search" wire:model.live="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-plus" class="btn-primary btn-square" wire:click.prevent="OpenAddEditModal"
                wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card class="shadow border">
        <x-mary-table :headers="$headers" :rows="$data" :sort-by="$sortBy" with-pagination show-empty-text
            per-page="perPage" :per-page-values="[10, 15, 25, 50, 100]">
            {{-- @scope('cell_id', $item)
                <strong>{{ $item->id }}</strong>
            @endscope --}}
            @scope('cell_id', $item)
                <strong>{{ $loop->iteration }}</strong>
            @endscope


            @scope('cell_first_name', $item)
                {{ $item->fullName() ?? '' }}
            @endscope

            @scope('cell_email', $item)
                <a href="mailto:{{ $item->email ?? '' }}" target="_blank" class="hover:text-blue-800">
                    {{ $item->email ?? '' }}
                </a>
            @endscope

            @scope('cell_phone', $item)
                <a href="tel:{{ $item->phone ?? '' }}" target="_blank" class="hover:text-blue-800">
                    {{ $item->phone ?? '' }}
                </a>
            @endscope

            @scope('cell_created_at', $item)
                <small>
                    {{ $item->created_at->format('d M, Y') }}
                </small>
            @endscope

            @scope('cell_subject', $item)
                {{ Str::limit($item->subject ?? '', 35) }}
            @endscope

            {{-- @scope('cell_status', $item)
            @if ($item->status)
                <x-mary-badge value="Seen" class="badge-primary" />
            @else
                <x-mary-badge value="Not Seen" class="badge-warning" />
            @endif
            @endscope --}}
            @scope('cell_status', $item)
                <div class="flex items-center gap-2">
                    {{-- Show current status --}}
                    @if ($item->status == \App\Models\ContactMail::STATUS_SEEN)
                        <x-mary-badge value="" class="badge-primary" />
                    @else
                        <x-mary-badge value="" class="badge-warning" />
                    @endif

                    {{-- Dropdown to change status --}}
                    <select wire:change="updateStatus({{ $item->id }}, $event.target.value)"
                        class="text-xs border rounded px-2 py-1 bg-white">
                        
                        @foreach (\App\Models\ContactMail::STATUS as $statusValue => $label)
                            <option value="{{ $statusValue }}" @selected($item->status == $statusValue)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endscope



            @scope('actions', $item)
                <div class="flex gap-3">
                    <div>
                        <x-mary-button icon="o-pencil-square" class="btn-sm btn-primary btn-circle" tooltip="Edit"
                            wire:click.prevent="OpenAddEditModal({{ $item->id }})" />
                    </div>
                    <div x-data="{
                        confirmDelete: function(userId) {
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
                    }">
                        <x-mary-button icon="o-trash" spinner="destroy('{{ $item->id }}')" tooltip="Delete"
                            class="btn-sm btn-error text-white btn-circle" @click="confirmDelete('{{ $item->id }}')" />
                    </div>
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    <form wire:submit.prevent="save">
        <x-mary-drawer wire:model="editModal" title="{{ Arr::has($request, 'id') ? 'Edit' : 'New' }} Contact Mail"
            subtitle="Here you can change the details of contact form" separator with-close-button close-on-escape
            class="w-11/12 lg:w-1/3" right>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 lg:col-span-6">
                    <x-mary-input wire:model="request.first_name" label="First Name" />
                </div>
                <div class="col-span-12 lg:col-span-6">
                    <x-mary-input wire:model="request.last_name" label="Last Name" />
                </div>
                <div class="col-span-12 lg:col-span-12">
                    <x-mary-input wire:model="request.email" label="Email" type="email" />
                </div>
                <div class="col-span-12 lg:col-span-12">
                    <x-forms.input-label label="Phone">
                        <x-admin.forms.phone-input wire:model="request.phone" />
                    </x-forms.input-label>
                </div>
                <div class="col-span-12 lg:col-span-12">
                    <x-mary-input wire:model="request.subject" label="Subject" />
                </div>
                <div class="col-span-12 lg:col-span-12">
                    <x-mary-textarea wire:model="request.message" label="Message" rows="5" />
                </div>
            </div>
            <x-slot:actions>
                <x-mary-button label="Save" class="btn-primary" icon="o-check" spinner="save" type="submit" />
                <x-mary-button label="Cancel" type="button" @click="$wire.editModal = false" />
            </x-slot:actions>
        </x-mary-drawer>
    </form>
</div>
