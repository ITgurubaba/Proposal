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
            </x-mary-card>

            {{-- Service Selection --}}
            <x-mary-card class="shadow border">
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
                                        <div class="font-semibold text-lg">{{ $service->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            @if ($service->pricing_type === 'bulk')
                                                Package Price: £{{ number_format($service->base_price, 2) }}
                                            @else
                                                Individual Pricing - Select items below
                                            @endif
                                            @if ($service->pricing_type === 'bulk')
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-sm">Base:</span>
                                                    <span
                                                        class="text-sm text-gray-500">£{{ number_format($service->base_price, 2) }}</span>
                                                    <span class="text-sm">Proposal Price:</span>
                                                    <input type="number" step="0.01"
                                                        wire:model.live="service_data.{{ $service->id }}.custom_price"
                                                        class="w-28 px-2 py-1 border border-gray-500 rounded 
                                                        text-gray-900 font-semibold bg-gray-50 
                                                        focus:border-primary focus:ring-primary" />

                                                </div>
                                            @endif
                                        </div>

                                        {{-- Individual Pricing Items --}}
                                        @if ($service->pricing_type === 'individual' && in_array($service->id, $selected_services))
                                            <div class="mt-3 space-y-2">
                                                <div class="text-sm font-medium">Select Items:</div>
                                                @foreach ($service->items as $item)
                                                    <div class="flex items-center gap-2 ml-4">
                                                        <input type="checkbox"
                                                            wire:model.live="service_data.{{ $service->id }}.items.{{ $item->id }}"
                                                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary" />
                                                        <span class="text-sm">{{ $item->name }}</span>
                                                        <span class="text-sm text-gray-800">
                                                            @if ($item->price !== null)
                                                                <input type="number" step="0.01"
                                                                    wire:model.live="service_data.{{ $service->id }}.item_prices.{{ $item->id }}"
                                                                    class="w-24 px-2 py-1 border border-gray-500 rounded 
                                                                    text-gray-900 font-semibold bg-gray-50
                                                                    placeholder:text-gray-900 placeholder:font-semibold
                                                                    focus:border-primary focus:ring-primary"
                                                                    placeholder="{{ $item->price }}" />
                                                            @else
                                                                (Included)
                                                            @endif

                                                        </span>
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
                                                                wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                placeholder="{{ $field->field_label }}" />
                                                        @elseif($field->field_type === 'number')
                                                            <input type="number"
                                                                wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                placeholder="{{ $field->field_label }}" />
                                                        @elseif($field->field_type === 'select')
                                                            <select
                                                                wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
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
                                                        @elseif($field->field_type === 'date')
                                                            <div class="grid grid-cols-2 gap-2">
                                                                <div>
                                                                    <label class="text-xs text-gray-600">Start
                                                                        Date</label>
                                                                    <input type="date"
                                                                        wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}.start_date"
                                                                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" />
                                                                </div>
                                                                <div>
                                                                    <label class="text-xs text-gray-600">End
                                                                        Date</label>
                                                                    <input type="date"
                                                                        wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}.end_date"
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
                                                if (
                                                    $service->pricing_type === 'individual' &&
                                                    isset($service_data[$service->id]['items'])
                                                ) {
                                                    foreach (
                                                        $service_data[$service->id]['items']
                                                        as $itemId => $selected
                                                    ) {
                                                        if ($selected) {
                                                            $itemContents[$itemId] =
                                                                $service->items->find($itemId)->contents ?? collect();
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if ($serviceContents->count() > 0 || !empty($itemContents))
                                                <div class="mt-4 border-t pt-4">
                                                    <div class="text-sm font-medium mb-3">Service Content:</div>

                                                    {{-- Service-level content --}}
                                                    @if ($serviceContents->count() > 0)
                                                        @foreach ($serviceContents as $content)
                                                            <div class="ml-4 mb-3">
                                                                <div class="font-semibold text-sm">
                                                                    {{ $content->title }}</div>
                                                                <div
                                                                    class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                                    {!! $content->content !!}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    {{-- Item-level content for selected items --}}
                                                    @if (!empty($itemContents))
                                                        @foreach ($itemContents as $itemId => $contents)
                                                            @if ($contents->count() > 0)
                                                                <div class="ml-4 mb-3">
                                                                    <div class="font-semibold text-sm text-primary">
                                                                        {{ $service->items->find($itemId)->name }}
                                                                        Content:
                                                                    </div>
                                                                    @foreach ($contents as $content)
                                                                        <div class="mt-2">
                                                                            <div class="font-medium text-sm">
                                                                                {{ $content->title }}</div>
                                                                            <div
                                                                                class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                                                {!! $content->content !!}
                                                                            </div>
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
