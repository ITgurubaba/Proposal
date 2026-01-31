<div>
    <x-mary-header subtitle="Showing the list of registered team members." >

        <x-slot:title class="text-4xl">
            Team Users
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
                           href="{{ route('admin::users.team.add') }}"
                           wire:navigate
            />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card class="shadow border">
        <x-mary-table :headers="$headers"
                      :rows="$data"
                      :sort-by="$sortBy"
                      with-pagination
        >
            @scope('cell_id', $user)
            <strong>{{ $user->id }}</strong>
            @endscope

            @scope('cell_image', $user)
            <img src="{{ $user->avatarUrl() }}" class="h-16 w-16 rounded-full" />
            @endscope

            @scope('cell_user_type', $user)
               <span class="badge bg-gray-800 text-white">
                   {{ $user->userType() ??'' }}
               </span>
            @endscope

            @scope('cell_name', $user)
            <a href="{{ route('admin::users.team.edit',['code'=>encryptId($user->id)]) }}"
               wire:navigate
               class="hover:text-blue-800 duration-300 transition"
            >
                {{ $user->fullName() }}
            </a>
            @endscope

            @scope('cell_email', $user)
            {{ $user->email ??'' }}
            @endscope

            @scope('cell_phone', $user)
            {{ $user->phone ??'' }}
            @endscope

            @scope('cell_status', $user)
            @if($user->status)
                <x-mary-badge value="Active" class="badge-primary" />
            @else
                <x-mary-badge value="Inactive" class="badge-danger" />
            @endif
            @endscope

            @scope('cell_online', $user)
            {{ $user->isOnline() ?'Yes':'No' }}
            @endscope

            @scope('actions', $user)
            <div class="flex gap-3">
                <div>
                    <x-mary-button icon="o-pencil-square"
                                   class="btn-sm btn-primary btn-circle"
                                   tooltip="Edit"
                                   href="{{ route('admin::users.team.edit',['code'=>encryptId($user->id)]) }}"
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
                                    Livewire.emit('destroy', userId);
                                }
                            });
                         }
                       }"
                >
                    <x-mary-button icon="o-trash"
                                   spinner="destroy({{ $user->id}})"
                                   tooltip="Delete"
                                   class="btn-sm btn-error text-white btn-circle"
                                   @click="confirmDelete({{ $user->id }})"
                    />
                </div>
            </div>
            @endscope

            {{--            @if ($data->isEmpty())--}}
            {{--                <x-slot:expandable>--}}
            {{--                    <tr>--}}
            {{--                        <td colspan="{{ count($headers) }}" class="text-center">--}}
            {{--                            No data available--}}
            {{--                        </td>--}}
            {{--                    </tr>--}}
            {{--                </x-slot:expandable>--}}
            {{--            @endif--}}
        </x-mary-table>
    </x-mary-card>
</div>
