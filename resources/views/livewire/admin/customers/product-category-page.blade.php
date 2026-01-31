<div>
    <x-mary-header subtitle="Showing the list of categories" >

        <x-slot:title class="text-4xl">
            Product Category
        </x-slot:title>

        <x-slot:middle class="!justify-end">

        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-plus"
                           class="btn-primary btn-square"
                           wire:click.prevent="openAddEditModal"
                           wire:loading.attr="disabled"
            />
        </x-slot:actions>
    </x-mary-header>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 lg:col-span-6">
            <x-mary-card class="shadow-sm border"
                         subtitle="Categories"
            >
                <ul class="max-w-xl flex flex-col">
                    @forelse($data as $item)
                        <li class="inline-flex items-center gap-x-3 py-3 px-4 cursor-grab text-sm font-medium bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-white mt-2 rounded-lg first:mt-0">
                            <div>
                                {{ $item->name ??'' }}
                            </div>
                            <div class="ms-auto">
                                <div class="flex gap-2">
                                    <x-mary-button class="btn-sm btn-circle btn-ghost"
                                                   icon="o-pencil"
                                                   tooltip="Edit"
                                                   wire:click.prevent="openAddEditModal({{ $item->id }})"
                                                   wire:loading.attr="disabled"
                                    />
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
                                        <x-mary-button class="btn-sm btn-circle btn-ghost"
                                                       icon="o-trash"
                                                       spinner="destroy('{{ $item->id}}')"
                                                       tooltip="Delete"
                                                       @click="confirmDelete('{{ $item->id }}')"
                                        />
                                    </div>

                                </div>
                            </div>
                        </li>
                         @if($item->children()->exists())
                             @foreach($item->children as $subItem)
                                <li class="inline-flex ms-10 items-center gap-x-3 py-3 px-4 cursor-grab text-sm font-medium bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-white mt-2 rounded-lg first:mt-0">
                                    <div>
                                        {{ $subItem->name ??'' }}
                                    </div>
                                    <div class="ms-auto">
                                        <div class="flex gap-2">
                                            <x-mary-button class="btn-sm btn-circle btn-ghost"
                                                           icon="o-pencil"
                                                           tooltip="Edit"
                                                           wire:click.prevent="openAddEditModal({{ $subItem->id }})"
                                                           wire:loading.attr="disabled"
                                            />
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
                                                <x-mary-button class="btn-sm btn-circle btn-ghost"
                                                               icon="o-trash"
                                                               spinner="destroy('{{ $subItem->id}}')"
                                                               tooltip="Delete"
                                                               @click="confirmDelete('{{ $subItem->id }}')"
                                                />
                                            </div>

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    @empty
                        <p class="text-sm">
                            No category
                        </p>
                    @endforelse
                </ul>
            </x-mary-card>
        </div>
        <div class="col-span-12 lg:col-span-6">
            <form wire:submit.prevent="Save">
                <x-mary-card class="shadow-sm border mb-5"
                             subtitle="{{ Arr::has($request,'id')?'Edit':'New' }} Category"
                >
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <x-mary-input wire:model.live="request.name"
                                          wire:change="generateSlug"
                                          label="Name"
                            />
                        </div>
                        <div class="col-span-12">
                            <x-forms.input-label label="Permalink:">
                                <div>
                                    <div class="flex flex-wrap gap-3">
                                        <div class="ms-2">
                                            <a class="seo-text text-decoration-underline">
                                    <span>
                                     {{ BackendHelper::getSlugPrefix('productCategory') }}
                                    </span>
                                                <span class="font-bold">
                                        {{ $request['slug'] ??'' }}
                                    </span>
                                            </a>
                                        </div>
                                        <div class="ms-2">
                                            @if($editSlug)
                                                <button class="btn btn-primary btn-sm py-1 px-2" style="font-size: 11px;" wire:click.prevent="SaveSlug">
                                                    Ok
                                                </button>
                                                <button class="btn btn-white btn-sm py-1 px-2" style="font-size: 11px;" wire:click.prevent="EditSlug(false)">
                                                    Cancel
                                                </button>
                                            @else
                                                <button class="btn btn-primary btn-sm py-1 px-2" style="font-size: 11px;" wire:click.prevent="EditSlug">
                                                    Edit
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </x-forms.input-label>

                            <div class="{{ $editSlug?'':'hidden' }} mt-2">
                                <x-mary-input wire:model.live="slug"
                                              label="Slug"
                                />
                            </div>
                            @error('request.slug')
                            <x-mary-alert title="{{ $message }}" icon="o-exclamation-triangle" class="alert-error my-3" dismissible  />
                            @enderror
                        </div>
                        <div class="col-span-12">
                            <x-mary-textarea wire:model="request.description"
                                             label="Description"
                            />
                        </div>
                        <div class="col-span-12">
                            <x-mary-select wire:model="request.parent_id"
                                           label="Parent Category"
                                           placeholder="Select Category"
                                           :options="$data"
                                           option-value="id"
                                           option-label="name"
                            />
                        </div>
                        <div class="col-span-12">
                            <div class="grid grid-cols-12 gap-5 mb-5">
                                <div class="col-span-6">
                                    <x-forms.input-label label="Category Image"
                                                         description="(Dimension:300 x 300 pixels)"
                                    >
                                        <x-forms.filepond  wire:model.live="request.image"
                                                           folder="images/"
                                                           accept="image/*"
                                        />
                                    </x-forms.input-label>
                                </div>
                                <div class="col-span-6">
                                    <x-admin.forms.image-viewer wire:model="request.image"
                                                                class="h-16"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <x-mary-input wire:model="request.position"
                                          label="Position"
                                          min="0"
                                          type="number"
                                          max="5000"
                            />
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <x-mary-select wire:model="request.status"
                                           label="Status"
                                           :options="BackendHelper::STATUS_OPTIONS"
                                           option-label="label"
                                           option-value="value"
                            />
                        </div>
                    </div>
                    <div class="mt-10 text-center">
                        <x-mary-button label="Save Category"
                                       class="btn-primary"
                                       spinner="{{Arr::has($request,'id')?'Save':'Submit'}}"
                                       type="submit"
                        />
                    </div>
                </x-mary-card>
                <x-mary-card class="shadow-sm border mb-5">
                    <div x-data="{open:false}">
                        <div class="card-header pb-2">
                            <div class="flex justify-between">
                                <span class="card-title">Search Engine Optimize</span>
                                <div class="card-action">
                                    <div>
                                        <x-mary-button
                                            class="btn btn-sm btn-circle btn-primary btn-square"
                                            tooltip="Edit"
                                            x-on:click="open = !(open)"
                                        >
                                           <span x-show="!open">
                                               <x-mary-icon name="o-pencil" />
                                           </span>
                                            <span x-show="open">
                                             <x-mary-icon name="o-minus" />
                                           </span>
                                        </x-mary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <h5 class="text-lg mb-0 pb-0 seo-text">{{ Str::limit($metaRequest['title'] ??($request['title'] ??''),110) }}</h5>
                                <a href="{{ BackendHelper::getSlugPrefix('productCategory') }}{{ $request['slug'] ??'' }}"
                                   class="text-sm"
                                   target="_blank"
                                   style="color: #006621"
                                >{{ BackendHelper::getSlugPrefix('productCategory') }}{{ $request['slug'] ??'' }}</a>
                                <p>
                                    {{ Str::limit($metaRequest['description'] ??($request['description'] ??''),160) }}
                                </p>
                            </div>
                            <div x-show="open" class="mt-5">
                                <div class="mb-5">
                                    <x-mary-input wire:model="metaRequest.title"
                                                  label="Meta Title"
                                                  hint="Max:(110 Char)"
                                    />
                                </div>
                                <div class="mb-5">
                                    <x-mary-textarea wire:model="metaRequest.description"
                                                     label="Meta Description"
                                                     hint="Max:(160 Char)"
                                    />
                                </div>
                                <div class=" mb-5">
                                    <x-mary-tags label="Meta Keywords"
                                                 wire:model="metaRequest.keywords"
                                                 hint="Hit enter to create a new tag"
                                                 class="tagInput"
                                    />
                                </div>
                                <div class="grid lg:grid-cols-4 gap-5 mb-5">
                                    <div class="col-span-2">
                                        <x-forms.input-label label="Meta Os Image"
                                                             description="(Dimension:1024 x 1024 pixels)"
                                        >
                                            <x-forms.filepond  wire:model.live="metaRequest.os_image"
                                                               folder="images/"
                                                               accept="image/*"
                                            />
                                        </x-forms.input-label>
                                    </div>
                                    <div class="col-span-2">
                                        <x-admin.forms.image-viewer wire:model="metaRequest.os_image"
                                                                    class="h-16"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-mary-card>
            </form>
        </div>
    </div>

</div>
