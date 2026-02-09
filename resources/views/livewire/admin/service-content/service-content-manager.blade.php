<div> {{-- 👈 SINGLE ROOT ELEMENT --}}

    <x-mary-header subtitle="Manage content for services and sub-services.">
        <x-slot:title class="text-4xl">Service Content</x-slot:title>
    </x-mary-header>

    <div class="mb-4 flex justify-end">
        <x-mary-toggle wire:model.live="showAllContent" label="Show All Content" />

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-3">
            <x-mary-card class="shadow border">
                <h3 class="text-lg font-semibold mb-4">Services</h3>

                @foreach ($services as $service)
                    <div class="border rounded-lg p-3">

                        {{-- SERVICE ROW --}}
                        <div class="cursor-pointer flex justify-between items-center
                            {{ $selected_service_id == $service->id && !$selected_item_id ? 'bg-primary/5' : '' }}"
                            wire:click="selectService({{ $service->id }})">
                            <div class="font-semibold">{{ $service->name }}</div>
                            <span class="text-sm text-gray-500">Service</span>
                        </div>

                        {{-- SERVICE PREVIEW --}}
                        @php
                            $serviceContent = $service->contents->whereNull('service_item_id')->first();
                        @endphp
                        @if ($showAllContent && $serviceContent && $selected_service_id != $service->id)
                            <div class="mt-2 ml-3 prose max-w-none text-sm">
                                {!! $serviceContent->content !!}
                            </div>
                        @endif



                        {{-- SERVICE EDITOR --}}
                        @if ($selected_service_id == $service->id && !$selected_item_id)
                            {{-- INSERT FIELD DROPDOWN --}}
                            <div class="mb-3 flex flex-wrap items-center gap-6">

                                {{-- INSERT TAG (STATIC / GENERIC) --}}
                                <div class="flex items-center gap-2 p-4">
                                    <label class="text-sm font-medium text-gray-600">
                                        Insert Tag:
                                    </label>

                                    <select class="border rounded px-2 py-1 text-sm"
                                        onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                        <option value="">-- Select Tag --</option>
                                        <option value="company_name">Company Name</option>
                                        <option value="employees">Employees</option>
                                        <option value="pay_frequency">Pay Frequency</option>
                                    </select>
                                </div>

                                {{-- INSERT FIELD (SERVICE BASED) --}}
                                @if (!empty($availableFields))
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm font-medium text-gray-600">
                                            Insert Field:
                                        </label>

                                        <select class="border rounded px-2 py-1 text-sm"
                                            onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                            <option value="">-- Select Field --</option>

                                            @foreach ($availableFields as $field)
                                                <option value="{{ $field['field_name'] }}">
                                                    {{ $field['field_label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            </div>


                            <div class="mt-3 border-t pt-3" wire:key="service-editor-{{ $service->id }}">
                                <x-admin.forms.ck-editor-input wire:model.defer="content" hide-tags />
                                <div class="mt-2">
                                    <x-mary-button class="btn-primary btn-sm" wire:click="saveContent">
                                        Save Content
                                    </x-mary-button>
                                </div>
                            </div>
                        @endif

                        {{-- SUB SERVICES --}}
                        @foreach ($service->items as $item)
                            <div class="ml-4 mt-2">

                                {{-- SUB ROW --}}
                                <div class="cursor-pointer text-sm
                                    {{ $selected_item_id == $item->id ? 'text-primary font-medium' : 'text-gray-700' }}"
                                        wire:click.stop="selectItem({{ $item->id }})">
                                        • {{ $item->name }}
                                </div>

                                {{-- SUB PREVIEW --}}
                                @php
                                    $itemContent = $service->contents->where('service_item_id', $item->id)->first();
                                @endphp
                                @if ($showAllContent && $itemContent && $selected_item_id != $item->id)
                                    <div class="ml-4 prose max-w-none text-sm">
                                        {!! $itemContent->content !!}
                                    </div>
                                @endif

                                {{-- SUB EDITOR --}}
                                @if ($selected_item_id == $item->id)
                                    {{-- INSERT FIELD DROPDOWN (SUB SERVICE) --}}
                                    <div class="mb-3 flex flex-wrap items-center gap-6">

                                        {{-- INSERT TAG (STATIC / GENERIC) --}}
                                        <div class="flex items-center gap-2 p-4">
                                            <label class="text-sm font-medium text-gray-600">
                                                Insert Tag:
                                            </label>

                                            <select class="border rounded px-2 py-1 text-sm"
                                                onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                                <option value="">-- Select Tag --</option>
                                                <option value="company_name">Company Name</option>
                                                <option value="employees">Employees</option>
                                                <option value="pay_frequency">Pay Frequency</option>
                                            </select>
                                        </div>

                                        {{-- INSERT FIELD (SERVICE BASED) --}}
                                        @if (!empty($availableFields))
                                            <div class="flex items-center gap-2">
                                                <label class="text-sm font-medium text-gray-600">
                                                    Insert Field:
                                                </label>

                                                <select class="border rounded px-2 py-1 text-sm"
                                                    onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                                    <option value="">-- Select Field --</option>

                                                    @foreach ($availableFields as $field)
                                                        <option value="{{ $field['field_name'] }}">
                                                            {{ $field['field_label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="mt-2 ml-4 border-l pl-3" wire:key="item-editor-{{ $item->id }}">
                                       
                                        <x-admin.forms.ck-editor-input wire:model.defer="content" hide-tags />
                                        <div class="mt-2">
                                            <x-mary-button class="btn-primary btn-sm" wire:click="saveContent">
                                                Save Content
                                            </x-mary-button>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach

                    </div>
                @endforeach
            </x-mary-card>
        </div>
    </div>

</div>
<script>
    /** @type {any} */
    window.activeCkEditor = window.activeCkEditor || null;

    function insertServiceField(fieldName) {
        if (!fieldName || !window.activeCkEditor) return;

        window.activeCkEditor.focus();
        window.activeCkEditor.insertText('[' + fieldName + ']');
    }
</script>
