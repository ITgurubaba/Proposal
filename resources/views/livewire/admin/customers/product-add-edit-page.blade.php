<div>
    <x-mary-header subtitle="Here you can add or edit the details of service.">

        <x-slot:title class="text-4xl">
            {{ checkData($product_id) ? 'Edit' : 'New' }} Product
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left" class="btn-light btn-sm btn-circle"
                href="{{ route('admin::ecommerce.products.list') }}" wire:navigate tooltip="Back to list" />
        </x-slot:actions>
    </x-mary-header>

    <form wire:submit.prevent="{{ checkData($product_id) ? 'Save' : 'Submit' }}">
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 lg:col-span-9">
                <x-mary-card class="shadow border mb-5">
                    <x-mary-tabs wire:model="currentTab">
                        <x-mary-tab name="main" icon="o-home" label="Main">
                            <div>
                                <x-mary-input wire:model.live="request.name" wire:change="generateSlug" label="Name"
                                    class="mb-5" />
                                <!--begin::Row-->
                                <div class="mb-5">
                                    <x-forms.input-label label="Permalink:">
                                        <div>
                                            <div class="flex flex-wrap gap-3">
                                                <div class="ms-2">
                                                    <a class="seo-text text-decoration-underline">
                                                        <span>
                                                            {{ BackendHelper::getSlugPrefix('products') }}
                                                        </span>
                                                        <span class="font-bold">
                                                            {{ $request['slug'] ?? '' }}
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="ms-2">
                                                    @if ($editSlug)
                                                        <button class="btn btn-primary btn-sm py-1 px-2"
                                                            style="font-size: 11px;" wire:click.prevent="SaveSlug">
                                                            Ok
                                                        </button>
                                                        <button class="btn btn-white btn-sm py-1 px-2"
                                                            style="font-size: 11px;"
                                                            wire:click.prevent="EditSlug(false)">
                                                            Cancel
                                                        </button>
                                                    @else
                                                        <button class="btn btn-primary btn-sm py-1 px-2"
                                                            style="font-size: 11px;" wire:click.prevent="EditSlug">
                                                            Edit
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </x-forms.input-label>

                                    <div class="{{ $editSlug ? '' : 'hidden' }} mt-2">
                                        <x-mary-input wire:model.live="slug" label="Slug" />
                                    </div>
                                    @error('request.slug')
                                        <x-mary-alert title="{{ $message }}" icon="o-exclamation-triangle"
                                            class="alert-error my-3" dismissible />
                                    @enderror
                                </div>
                                <!--end::Row-->
                                <x-mary-input wire:model="request.sku" label="SKU" class="mb-5" />

                                <x-mary-input wire:model="request.stocks" label="Stocks Available" class="mb-5"
                                    type="number" min="0" />
                                <x-mary-textarea wire:model="request.description" label="Short Description"
                                    class="mb-5" />

                                <x-mary-choices wire:model="selectedCategories" label="Categories" :options="$categories"
                                    option-label="name" option-value="id" class="mary-choices" />


                                <x-forms.input-label label="Content" class="mb-5">
                                    <x-admin.forms.ck-editor-input wire:model="request.content" />
                                </x-forms.input-label>
                                <div class="mb-5">
                                    <x-mary-choices wire:model="request.related_products" label="Related Products"
                                        :options="$products" option-label="name" option-value="id" option-avatar="image"
                                        search-function="searchProducts" searchable>
                                        @scope('item', $item)
                                            <x-mary-list-item :item="$item" sub-value="sku">
                                                <x-slot:avatar>
                                                    <img src="{{ $item->thumbnail() }}" class="h-8 w-8" />
                                                </x-slot:avatar>
                                            </x-mary-list-item>
                                        @endscope
                                    </x-mary-choices>
                                </div>
                                <div class="mb-5">
                                    <x-mary-tags wire:model="request.tags" label="Tags" />
                                </div>
                            </div>
                        </x-mary-tab>
                        <x-mary-tab name="media" icon="o-photo" label="Media">
                            <div>
                                <div class="grid grid-cols-12 gap-5 mb-5">
                                    <div class="col-span-6">
                                        <x-forms.input-label label="Product Main Image"
                                            description="(Dimension:270 x 300 pixels)">
                                            <x-forms.filepond wire:model.live="request.image" folder="images/"
                                                accept="image/*" />
                                        </x-forms.input-label>
                                    </div>
                                    <div class="col-span-6">
                                        <x-admin.forms.image-viewer wire:model="request.image" class="h-16" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-5 mb-5">
                                    <div class="col-span-6">
                                        <x-forms.input-label label="Product Hover Image"
                                            description="(Dimension:270 x 300 pixels)">
                                            <x-forms.filepond wire:model.live="request.hover_image" folder="images/"
                                                accept="image/*" />
                                        </x-forms.input-label>
                                    </div>
                                    <div class="col-span-6">
                                        <x-admin.forms.image-viewer wire:model="request.hover_image" class="h-16" />
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <x-forms.input-label label="Other Product Images">
                                        @foreach ($productImages as $i => $item)
                                            <div class="grid grid-cols-12 gap-5 mb-5">
                                                <div class="col-span-6">
                                                    <x-forms.filepond
                                                        wire:model.live="productImages.{{ $i }}.path"
                                                        folder="images/" accept="image/*" />
                                                </div>
                                                <div class="col-span-6">
                                                    <div class="flex flex-wrap gap-4 items-center">
                                                        <div>
                                                            @if (checkData($item['path']))
                                                                <img src="{{ asset($item['path'] ?? '') }}"
                                                                    class="h-16" />
                                                            @else
                                                                <img src="{{ asset('assets/default/no-upload.png') }}"
                                                                    class="h-16" />
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <x-mary-button
                                                                class="btn-circle btn-error text-white btn-sm"
                                                                icon="o-trash"
                                                                wire:click.prevent="removeProductImage({{ $i }})" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <x-mary-button class="btn-block btn-light" label="Add Image"
                                            icon="o-plus-circle" wire:click.prevent="addNewProductImage"
                                            spinner="addNewProductImage" wire:loading.attr="disabled" />
                                    </x-forms.input-label>
                                </div>
                            </div>
                        </x-mary-tab>
                        <x-mary-tab name="pricing" label="Pricing" icon="o-currency-dollar">
                            <!-- Main Pricing -->
                            <div class="grid grid-cols-12 gap-5 {{ $request['has_pricing'] ? 'hidden' : '' }}">
                                <div class="col-span-6">
                                    <x-mary-input wire:model="request.market_price" label="Market Price"
                                        step="any" type="number" />
                                </div>
                                <div class="col-span-6">
                                    <x-mary-input wire:model="request.selling_price" label="Selling Price"
                                        step="any" type="number" />
                                </div>
                            </div>
                            <!-- Attribure Pricing -->
                            <div class="grid grid-cols-12 gap-5 {{ $request['has_pricing'] ? '' : 'hidden' }}">
                                @forelse($productPricing as $i=>$item)
                                    <div class="col-span-12">
                                        <x-mary-card class="border border-dashed relative">
                                            <div class="absolute top-0 right-0">
                                                <x-mary-button icon="o-trash"
                                                    class="btn-error btn-sm btn-circle text-white"
                                                    wire:click.prevent="removeProductPricing({{ $i }})"
                                                    wire:loading.attr="disabled" />
                                            </div>
                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-6">
                                                    <x-mary-select :options="$productTypeListOptions" option-label="label"
                                                        option-value="value" label="Attribute type"
                                                        wire:model.live="productPricing.{{ $i }}.product_type" />
                                                </div>
                                                <div class="col-span-6">
                                                    @if ($item['product_type'] == \App\Models\ProductType::TYPE_SIZE)
                                                        <x-mary-select
                                                            wire:model="productPricing.{{ $i }}.product_type_id"
                                                            :options="$productSizeAttributes" option-value="id" option-label="name"
                                                            label="Select Size" placeholder="Select Option"
                                                            required />
                                                    @elseif($item['product_type'] == \App\Models\ProductType::TYPE_COLOR)
                                                        <x-mary-select
                                                            wire:model="productPricing.{{ $i }}.product_type_id"
                                                            :options="$productColorAttributes" option-value="id" option-label="name"
                                                            label="Select Color" placeholder="Select Option"
                                                            required />
                                                    @else
                                                        <div class="grid grid-cols-12 gap-3">
                                                            <div class="col-span-6">
                                                                <x-mary-select
                                                                    wire:model="productPricing.{{ $i }}.product_type_id"
                                                                    :options="$productSizeAttributes" option-value="id"
                                                                    option-label="name" label="Select Size"
                                                                    placeholder="Select Option" required />
                                                            </div>
                                                            <div class="col-span-6">
                                                                <x-mary-select
                                                                    wire:model="productPricing.{{ $i }}.sub_product_type_id"
                                                                    :options="$productColorAttributes" option-value="id"
                                                                    option-label="name" label="Select Color"
                                                                    placeholder="Select Option" required />
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-span-4">
                                                    <x-mary-input
                                                        wire:model="productPricing.{{ $i }}.market_price"
                                                        label="Market Price" step="any" type="number" />
                                                </div>
                                                <div class="col-span-4">
                                                    <x-mary-input
                                                        wire:model="productPricing.{{ $i }}.selling_price"
                                                        label="Selling Price" step="any" type="number" />
                                                </div>
                                                <div class="col-span-4">
                                                    <x-mary-input
                                                        wire:model="productPricing.{{ $i }}.stocks"
                                                        label="Stocks" step="any" type="number" />
                                                </div>
                                            </div>
                                        </x-mary-card>
                                    </div>
                                @empty
                                    <div class="col-span-12">
                                        <x-mary-card>
                                            <p class="text-center">
                                                No pricing added
                                            </p>
                                        </x-mary-card>
                                    </div>
                                @endforelse
                                <div class="col-span-12 mt-3">
                                    <div class="flex justify-end">
                                        <x-mary-button class="btn-success text-white" icon="o-plus" label="Add"
                                            wire:click.prevent="addNewProductPricing"
                                            spinner="addNewProductPricing" />
                                    </div>
                                </div>
                            </div>
                        </x-mary-tab>
                        <x-mary-tab name="shipping" label="Shipping" icon="o-truck">
                            <div class="grid grid-cols-12 gap-5">
                                <div class="col-span-12">
                                    <x-mary-input wire:model="request.weight" label="Weight" type="number"
                                        min="0" />
                                </div>
                                <div class="col-span-4">
                                    <x-mary-input wire:model="request.width" label="Width" type="number"
                                        min="0" />
                                </div>
                                <div class="col-span-4">
                                    <x-mary-input wire:model="request.length" label="Length" type="number"
                                        min="0" />
                                </div>
                                <div class="col-span-4">
                                    <x-mary-input wire:model="request.height" label="Height" type="number"
                                        min="0" />
                                </div>
                            </div>
                        </x-mary-tab>
                    </x-mary-tabs>
                </x-mary-card>

                <x-mary-card class="shadow border mb-5">
                    <div x-data="{ open: false }">
                        <div class="card-header pb-2">
                            <div class="flex justify-between">
                                <span class="card-title">Search Engine Optimize</span>
                                <div class="card-action">
                                    <div>
                                        <x-mary-button class="btn btn-sm btn-circle btn-primary btn-square"
                                            tooltip="Edit" x-on:click="open = !(open)">
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
                                <h5 class="text-lg mb-0 pb-0 seo-text">
                                    {{ Str::limit($metaRequest['title'] ?? ($request['title'] ?? ''), 110) }}</h5>
                                <a href="{{ BackendHelper::getSlugPrefix('products') }}{{ $request['slug'] ?? '' }}"
                                    class="text-sm" target="_blank"
                                    style="color: #006621">{{ BackendHelper::getSlugPrefix('products') }}{{ $request['slug'] ?? '' }}</a>
                                <p>
                                    {{ Str::limit($metaRequest['description'] ?? ($request['description'] ?? ''), 160) }}
                                </p>
                            </div>
                            <div x-show="open" class="mt-5">
                                <div class="mb-5">
                                    <x-mary-input wire:model="metaRequest.title" label="Meta Title"
                                        hint="Max:(110 Char)" />
                                </div>
                                <div class="mb-5">
                                    <x-mary-textarea wire:model="metaRequest.description" label="Meta Description"
                                        hint="Max:(160 Char)" />
                                </div>
                                <div class=" mb-5">
                                    <x-mary-tags label="Meta Keywords" wire:model="metaRequest.keywords"
                                        hint="Hit enter to create a new tag" class="tagInput" />
                                </div>
                                <div class="grid lg:grid-cols-4 gap-5 mb-5">
                                    <div class="col-span-2">
                                        <x-forms.input-label label="Meta Os Image"
                                            description="(Dimension:1024 x 1024 pixels)">
                                            <x-forms.filepond wire:model.live="metaRequest.os_image" folder="images/"
                                                accept="image/*" />
                                        </x-forms.input-label>
                                    </div>
                                    <div class="col-span-2">
                                        <x-admin.forms.image-viewer wire:model="metaRequest.os_image"
                                            class="h-16" />
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
                            <x-mary-button label="Save Product" class="btn-primary"
                                spinner="{{ checkData($product_id) ? 'Save' : 'Submit' }}" type="submit" />
                        </div>
                        <div class="flex mb-5">
                            <x-mary-toggle wire:model="request.status" label="Status" value="1"
                                :checked="(bool) $request['status']" />
                        </div>
                        <div class="flex mb-5">
                            <x-mary-toggle wire:model="request.is_discount_applied" label="Slash Market Price"
                                value="1" :checked="(bool) $request['is_discount_applied']" />
                        </div>
                        <div class="flex mb-5">
                            <x-mary-toggle wire:model.live="request.has_pricing" label="Have Attribute Pricing ?"
                                value="1" :checked="(bool) $request['has_pricing']" />
                        </div>
                        <div class="flex mb-5">
                            <x-mary-toggle wire:model="request.is_featured" label="Is Featured Product"
                                value="1" :checked="(bool) $request['is_featured']" />
                        </div>
                    </x-mary-card>
                </div>
            </div>
        </div>
    </form>
</div>
