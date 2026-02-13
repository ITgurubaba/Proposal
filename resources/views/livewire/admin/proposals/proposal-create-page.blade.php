<div>
    <x-mary-header subtitle="Create a new proposal for a client.">

        <x-slot:title class="text-4xl">
            Create Proposal
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left" class="btn-secondary" href="{{ route('admin::proposals:list') }}"
                wire:navigate />
        </x-slot:actions>

    </x-mary-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Client Selection --}}
            <x-mary-card class="shadow border">
                <h3 class="text-lg font-semibold mb-4">Select Client</h3>
                <x-mary-select label="Client" wire:model="client_id" :options="$clients" option-label="company_name"
                    option-value="id" placeholder="Choose a client" required />
                <a href="{{ route('admin::company.clients.add') }}" class="text-sm text-primary pt-2 hover:underline">
                    + Add New Client
                </a>

            </x-mary-card>

            {{-- Service Selection --}}
            <x-mary-card class="shadow border mt-4">
                <h3 class="text-lg font-semibold mb-4">Select Services</h3>

                @if ($services->count() > 0)
                    <div class="space-y-4">
                        @foreach ($services as $service)
                            <div
                                class="border rounded-lg p-4 {{ in_array($service->id, $selected_services) ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" wire:model.live="selected_services"
                                        value="{{ $service->id }}"
                                        class="mt-1 w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary" />
                                    <div class="flex-1">

                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-lg">
                                                {{ $service->name }}
                                            </div>

                                            @if (in_array($service->id, $selected_services))
                                                <div class="text-primary font-bold text-lg">
                                                    £{{ number_format($this->calculateServicePrice($service->id), 2) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-600">
                                            @if ($service->pricing_type === 'bulk')
                                                Package Price: £{{ number_format($service->base_price, 2) }}
                                            @else
                                                Individual Pricing - Select items below
                                            @endif
                                            @if ($service->pricing_type === 'bulk')
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-sm">Revised Price:</span>
                                                    <input type="number" step="0.01"
                                                        wire:model.live="service_data.{{ $service->id }}.custom_price"
                                                        class="w-28 px-2 py-1 border border-gray-500 rounded 
                                                        text-gray-900 font-semibold bg-gray-50 
                                                        focus:border-primary focus:ring-primary" />

                                                </div>
                                            @endif
                                        </div>

                                        {{-- Individual Pricing Items --}}
                                        @if (in_array($service->id, $selected_services) && $service->items->count())
                                            <div class="mt-3 space-y-2">
                                                <div class="text-sm font-medium">Select Items:</div>

                                                @foreach ($service->items as $item)
                                                    <div class="flex items-center gap-2 ml-4">

                                                        @if ($service->pricing_type === 'individual')
                                                            {{-- INDIVIDUAL → checkbox + price --}}
                                                            <input type="checkbox"
                                                                wire:model.live="service_data.{{ $service->id }}.items.{{ $item->id }}"
                                                                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
                                                        @endif

                                                        <span class="text-sm">{{ $item->name }}</span>

                                                        @if ($service->pricing_type === 'individual')
                                                            <span class="text-sm text-gray-800">
                                                                @if ($item->price !== null)
                                                                    <input type="number" step="0.01"
                                                                        wire:model.live="service_data.{{ $service->id }}.item_prices.{{ $item->id }}"
                                                                        class="w-24 px-2 py-1 border border-gray-500 rounded 
                                                                        text-gray-900 font-semibold bg-gray-50
                                                                        focus:border-primary focus:ring-primary"
                                                                        placeholder="{{ $item->price }}" />
                                                                    {{-- Original Price --}}
                                                                    <span class="text-gray-400 line-through">
                                                                        £{{ number_format($item->price, 2) }}
                                                                    </span>
                                                                @else
                                                                    (Included)
                                                                @endif
                                                            </span>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif


                                        {{-- Service Fields --}}
                                        @if (in_array($service->id, $selected_services) && $service->fields->count() > 0)
                                            <div class="mt-3 space-y-2">
                                                <div class="text-sm font-medium">Service Details:</div>
                                                @foreach ($service->fields as $field)
                                                    <div class="ml-4">
                                                        <label
                                                            class="text-sm font-medium">{{ $field->field_label }}</label>
                                                        @if ($field->field_type === 'text')
                                                            <input type="text"
                                                                wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                placeholder="{{ $field->field_label }}" />
                                                        @elseif($field->field_type === 'number')
                                                            <input type="number"
                                                                wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                placeholder="{{ $field->field_label }}" />
                                                        @elseif($field->field_type === 'select')
                                                            <select
                                                                wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                                <option value="">Select {{ $field->field_label }}
                                                                </option>
                                                                @if (!empty($field->options) && is_array($field->options))
                                                                    @foreach ($field->options as $option)
                                                                        @if (!empty(trim($option)))
                                                                            <option value="{{ trim($option) }}">
                                                                                {{ trim($option) }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            {{-- SINGLE DATE --}}
                                                        @elseif($field->field_type === 'date')
                                                            <input type="date"
                                                                wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />

                                                            {{-- DATE RANGE --}}
                                                        @elseif($field->field_type === 'date_range')
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <div>
                                                                    <label class="text-xs text-gray-600">Start
                                                                        Date</label>
                                                                    <input type="date"
                                                                        wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}.start"
                                                                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                                                                </div>
                                                                <div>
                                                                    <label class="text-xs text-gray-600">End
                                                                        Date</label>
                                                                    <input type="date"
                                                                        wire:model.live="service_data.{{ $service->id }}.fields.{{ $field->id }}.end"
                                                                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Service Content --}}
                                        @if (in_array($service->id, $selected_services))
                                            @php
                                                $serviceContents = $service->contents->whereNull('service_item_id');
                                                $itemContents = [];

                                                if ($service->pricing_type === 'individual') {
                                                    // Only selected items
                                                    if (isset($service_data[$service->id]['items'])) {
                                                        foreach (
                                                            $service_data[$service->id]['items']
                                                            as $itemId => $selected
                                                        ) {
                                                            if ($selected) {
                                                                $item = $service->items->find($itemId);
                                                                if ($item) {
                                                                    $itemContents[$itemId] =
                                                                        $item->contents ?? collect();
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    // BULK SERVICE → Show ALL items automatically
                                                    foreach ($service->items as $item) {
                                                        $itemContents[$item->id] = $item->contents ?? collect();
                                                    }
                                                }

                                            @endphp

                                            @if ($serviceContents->count() > 0 || !empty($itemContents))
                                                <div class="mt-4 border-t pt-4">
                                                    <div class="text-sm font-medium mb-3">Service Content:</div>


                                                    {{-- Service-level content --}}
                                                    @foreach ($serviceContents as $content)
                                                        <div wire:key="service-content-{{ $content->id }}"
                                                            class="ml-4 mb-3">

                                                            <div class="flex items-center justify-between gap-3">

                                                                <div class="flex items-center gap-2">
                                                                    {{-- ✅ INCLUDE / EXCLUDE CHECKBOX --}}
                                                                    <input type="checkbox"
                                                                        wire:model="selected_service_contents"
                                                                        value="{{ $content->id }}"
                                                                        class="w-4 h-4 text-primary border-gray-300 rounded" />

                                                                    <div class="font-semibold text-sm">
                                                                        {{ $content->title }}
                                                                    </div>
                                                                </div>

                                                                {{-- ✏️ Edit button --}}


                                                                <button type="button"
                                                                    class="text-xs text-primary underline"
                                                                    wire:click="toggleEditor({{ $content->id }})">
                                                                   
                                                                    {{ in_array($content->id, $open_service_editors) ? 'Minimize' : 'Edit' }}
                                                                </button>

                                                            </div>

                                                            {{-- Preview --}}
                                                            <div
                                                                class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                                {!! $this->getProcessedAnyServiceContent($content) !!}
                                                            </div>

                                                            {{-- Editor --}}
                                                            @if (in_array($content->id, $open_service_editors))
                                                                {{-- INSERT FIELD DROPDOWN --}}
                                                                <div class="mb-3 flex flex-wrap items-center gap-6">

                                                                    {{-- INSERT TAG --}}
                                                                    <div class="flex items-center gap-2 pr-4 ">

                                                                        <x-admin.static-tags-dropdown />

                                                                    </div>

                                                                    {{-- INSERT FIELD (SERVICE BASED) --}}
                                                                    @if ($service->fields->count())
                                                                        <div class="flex items-center gap-2">
                                                                            <label
                                                                                class="text-sm font-medium text-gray-600">
                                                                                Insert Variable:
                                                                            </label>

                                                                            <select
                                                                                class="border rounded px-2 py-1 text-sm"
                                                                                onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                                                                <option value="">-- Select
                                                                                    Variable
                                                                                    --</option>

                                                                                @foreach ($service->fields as $field)
                                                                                    {{-- NORMAL FIELD --}}
                                                                                    @if ($field->field_type !== 'date_range')
                                                                                        <option
                                                                                            value="{{ $field->field_name }}">
                                                                                            {{ $field->field_label }}
                                                                                        </option>
                                                                                    @else
                                                                                        {{-- DATE RANGE SPECIAL --}}
                                                                                        <option
                                                                                            value="{{ $field->field_name }}">
                                                                                            {{ $field->field_label }}
                                                                                            (Full)
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $field->field_name }}_start">
                                                                                            {{ $field->field_label }}
                                                                                            Start
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $field->field_name }}_end">
                                                                                            {{ $field->field_label }}
                                                                                            End
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach

                                                                            </select>
                                                                        </div>
                                                                    @endif

                                                                </div>

                                                                {{-- CKEDITOR --}}
                                                                <div class="mt-2">
                                                                    <x-admin.forms.ck-editor-input
                                                                        wire:key="editor-service-{{ $content->id }}"
                                                                        wire:model.live.debounce.300ms="edited_contents.service_{{ $content->id }}"
                                                                        data-height="120" hide-tags />
                                                                </div>
                                                            @endif

                                                        </div>
                                                    @endforeach



                                                    {{-- Item-level content for selected items --}}
                                                    @if (!empty($itemContents))
                                                        @foreach ($itemContents as $itemId => $contents)
                                                            @if ($contents->count() > 0)
                                                                <div class="ml-4 mb-3">
                                                                    {{-- <div class="font-semibold text-sm text-primary">
                                                                        {{ $service->items->find($itemId)->name }}
                                                                        Content:
                                                                    </div> --}}

                                                                    @foreach ($contents as $content)
                                                                        <div wire:key="item-content-{{ $content->id }}"
                                                                            class="mt-2">

                                                                            <div
                                                                                class="flex items-center justify-between gap-3">

                                                                                <div class="flex items-center gap-2">
                                                                                    {{-- ✅ INCLUDE / EXCLUDE CHECKBOX --}}
                                                                                    <input type="checkbox"
                                                                                        wire:model="selected_service_contents"
                                                                                        value="{{ $content->id }}"
                                                                                        class="w-4 h-4 text-primary border-gray-300 rounded" />

                                                                                    <div class="font-medium text-sm">
                                                                                        {{ $content->title }}
                                                                                    </div>
                                                                                </div>

                                                                                {{-- ✏️ Edit --}}
                                                                                <button type="button"
                                                                                    class="text-xs text-primary underline"
                                                                                    wire:click="toggleEditor({{ $content->id }})">
                                                                                    {{ in_array($content->id, $open_service_editors) ? 'Minimize' : 'Edit' }}
                                                                                </button>

                                                                            </div>

                                                                            {{-- Preview --}}
                                                                            <div
                                                                                class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                                                {!! $this->getProcessedAnyServiceContent($content) !!}
                                                                            </div>

                                                                            {{-- Editor --}}
                                                                            @if (in_array($content->id, $open_service_editors))
                                                                                {{-- INSERT FIELD DROPDOWN --}}
                                                                                <div
                                                                                    class="mb-3 flex flex-wrap items-center gap-6">

                                                                                    {{-- INSERT TAG --}}
                                                                                    <div
                                                                                        class="flex items-center gap-2 pr-4">
                                                                                        <x-admin.static-tags-dropdown />
                                                                                    </div>

                                                                                    {{-- INSERT FIELD (SERVICE BASED) --}}
                                                                                    @if ($service->fields->count())
                                                                                        <div
                                                                                            class="flex items-center gap-2">
                                                                                            <label
                                                                                                class="text-sm font-medium text-gray-600">
                                                                                                Insert Field:
                                                                                            </label>

                                                                                            <select
                                                                                                class="border rounded px-2 py-1 text-sm"
                                                                                                onchange="insertServiceField(this.value); this.selectedIndex = 0;">
                                                                                                <option value="">
                                                                                                    -- Select Field
                                                                                                    --</option>

                                                                                                @foreach ($service->fields as $field)
                                                                                                    {{-- NORMAL FIELD --}}
                                                                                                    @if ($field->field_type !== 'date_range')
                                                                                                        <option
                                                                                                            value="{{ $field->field_name }}">
                                                                                                            {{ $field->field_label }}
                                                                                                        </option>
                                                                                                    @else
                                                                                                        {{-- DATE RANGE SPECIAL --}}
                                                                                                        <option
                                                                                                            value="{{ $field->field_name }}">
                                                                                                            {{ $field->field_label }}
                                                                                                            (Full)
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="{{ $field->field_name }}_start">
                                                                                                            {{ $field->field_label }}
                                                                                                            Start
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="{{ $field->field_name }}_end">
                                                                                                            {{ $field->field_label }}
                                                                                                            End
                                                                                                        </option>
                                                                                                    @endif
                                                                                                @endforeach

                                                                                            </select>
                                                                                        </div>
                                                                                    @endif

                                                                                </div>


                                                                                <div class="mt-2">
                                                                                    <x-admin.forms.ck-editor-input
                                                                                        wire:key="editor-item-{{ $content->id }}"
                                                                                        wire:model.live.debounce.300ms="edited_contents.service_{{ $content->id }}"
                                                                                        data-height="120" hide-tags />
                                                                                </div>
                                                                            @endif

                                                                        </div>
                                                                    @endforeach

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        No active services available. Please activate services first.
                    </div>
                @endif
            </x-mary-card>

            {{-- Content Selection and Editing --}}
            <x-mary-card class="shadow border mt-4 mb-4">
                <h3 class="text-lg font-semibold mb-4">Proposal Content</h3>



                {{-- Other Contents --}}

                @if ($this->availableOtherContents->count() > 0)

                    <div>
                        <div class="text-sm font-medium mb-3">Other Contents:</div>
                        <div class="space-y-3">
                            @foreach ($this->availableOtherContents as $content)
                                <div
                                    class="border rounded-lg p-4 mb-2 {{ in_array($content->id, $selected_other_contents) ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" wire:model.live="selected_other_contents"
                                            value="{{ $content->id }}"
                                            class="mt-1 w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary" />

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">

                                                <div class="font-semibold text-sm">
                                                    {{ $content->title }}
                                                </div>

                                                @if (in_array($content->id, $selected_other_contents))
                                                    <button type="button" class="text-xs text-primary underline"
                                                        wire:click="toggleOtherEditor({{ $content->id }})">
                                                        {{ in_array($content->id, $open_other_editors) ? 'Minimize' : 'Edit' }}
                                                    </button>
                                                @endif

                                            </div>
                                            @if (in_array($content->id, $open_other_editors))
                                                <div class="mt-3">
                                                    <label class="text-xs text-gray-600 mb-1 block">
                                                        Edit Content (Proposal Specific):
                                                    </label>

                                                    <x-admin.forms.ck-editor-input
                                                        wire:model="edited_contents.other_{{ $content->id }}"
                                                        data-height="150" :value="$edited_contents['other_' . $content->id] ??
                                                            $content->content" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @endif

                @if ($this->availableServiceContents->count() === 0 && $this->availableOtherContents->count() === 0)
                    <div class="text-center py-4 text-gray-500 text-sm">
                        No content available. Select services to see service contents or add other contents.
                    </div>
                @endif
            </x-mary-card>
        </div>

        {{-- Right Column - Summary --}}
        <div class="lg:col-span-1">
            <x-mary-card class="shadow border sticky top-4">
                <h3 class="text-lg font-semibold mb-4">Proposal Summary</h3>

                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-600">Selected Services:</div>
                        <div class="font-semibold">{{ count($selected_services) }}</div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="text-sm text-gray-600">Total Price:</div>
                        <div class="text-3xl font-bold text-primary">£{{ number_format($total_price, 2) }}</div>
                    </div>

                    <div class="pt-4">
                        <x-mary-button type="button" class="btn-primary w-full" wire:click="saveProposal"
                            :disabled="!$client_id || count($selected_services) === 0">
                            Create Proposal
                        </x-mary-button>
                    </div>
                </div>
            </x-mary-card>
        </div>
    </div>
</div>

<script>
    window.activeCkEditor = window.activeCkEditor || null;

    function insertServiceField(tag) {
        if (!tag || !window.activeCkEditor) return;

        window.activeCkEditor.focus();
        window.activeCkEditor.insertText('[' + tag + ']');
    }
</script>
