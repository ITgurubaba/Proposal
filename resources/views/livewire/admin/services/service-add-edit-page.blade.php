<div>
    <x-mary-header subtitle="Here you can add or edit the details of service." >

        <x-slot:title class="text-4xl">
            {{ checkData($service_id)?'Edit':'New' }} Service
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left"
                           class="btn-light btn-sm btn-circle"
                           x-on:click=""
                           href="{{ back()->getTargetUrl() }}"
                           wire:navigate
                           tooltip="Back to list"
            />
        </x-slot:actions>
    </x-mary-header>

    <form wire:submit.prevent="{{checkData($service_id)?'Save':'Submit'}}">
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 lg:col-span-9">
                <x-mary-card class="shadow border mb-5">
                    <x-mary-input wire:model.live="request.name"
                                  wire:change="generateSlug"
                                  label="Name"
                                  class="mb-5"
                    />
                    <!--begin::Row-->
                    <div class="mb-5">
                        <x-forms.input-label label="Permalink:">
                            <div>
                                <div class="flex flex-wrap gap-3">
                                    <div class="ms-2">
                                        <a class="seo-text text-decoration-underline">
                                    <span>
                                     {{ BackendHelper::getSlugPrefix('service') }}
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
                    <!--end::Row-->
                    <x-mary-textarea wire:model="request.description"
                                     label="Short Description"
                                     class="mb-5"
                    />
                    <x-forms.input-label label="Content" class="mb-5">
                        <x-admin.forms.ck-editor-input wire:model="request.content" />
                    </x-forms.input-label>
                </x-mary-card>

                <x-mary-card class="shadow border mb-5">
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
                                <a href="{{ BackendHelper::getSlugPrefix('service') }}{{ $request['slug'] ??'' }}"
                                   class="text-sm"
                                   target="_blank"
                                   style="color: #006621"
                                >{{ BackendHelper::getSlugPrefix('service') }}{{ $request['slug'] ??'' }}</a>
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
                                            <x-forms.filepond  wire:model="metaRequest.os_image"
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
            </div>
            <div class="col-span-12 lg:col-span-3 relative">
                <div class="sticky top-0 left-0">
                    <x-mary-card class="shadow border">
                        <div class="mb-5">
                            <x-mary-button label="Save Service"
                                           class="btn-primary"
                                           spinner="{{checkData($service_id)?'Save':'Submit'}}"
                                           type="submit"
                            />
                        </div>
                        <div class="flex mb-5">
                            <x-mary-toggle wire:model="request.status"
                                           label="Status"
                                           value="1"
                                           :checked="(bool)$request['status']"
                            />
                        </div>
                        <div class="grid lg:grid-cols-4 gap-5 mb-5">
                            <div class="col-span-4">
                                <x-forms.input-label label="Thumbnail"
                                                     description="(Dimension:1024 x 1024 pixels)"
                                >
                                    <x-forms.filepond  wire:model="request.image"
                                                       folder="images/"
                                                       accept="image/*"
                                    />
                                </x-forms.input-label>
                            </div>
                            <div class="col-span-4">
                                <x-admin.forms.image-viewer wire:model="request.image"
                                                            class="h-16"
                                />
                            </div>
                        </div>
                    </x-mary-card>
                </div>
            </div>
        </div>
    </form>
</div>
