<div>
    <x-mary-header subtitle="Showing the list of products." >

        <x-slot:title class="text-4xl">
            Products
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
                           link="{{ route('admin::ecommerce.products.add') }}"
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

            @scope('cell_image', $item)
               <img src="{{ $item->thumbnail() }}" class="h-16" />
            @endscope

            @scope('cell_name', $item)
            {{ $item->name ??'' }}
            @endscope

            @scope('cell_category_id', $item)
              <div class="flex flex-wrap gap-2 max-w-40">
                  @foreach($item->categories as $category)
                      <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-white">
                          {{ $category->name ??'' }}
                      </span>
                  @endforeach
              </div>
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
                                   href="{{ route('admin::ecommerce.products.edit',['product_id'=>$item->id]) }}"
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
