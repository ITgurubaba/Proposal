<div>
    <x-mary-header subtitle="Create a new proposal for a client.">

        <x-slot:title class="text-4xl">
            Create Proposal
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left"
                           class="btn-secondary"
                           href="{{ route('admin::proposals:list') }}"
                           wire:navigate
            />
        </x-slot:actions>

    </x-mary-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Client Selection --}}
            <x-mary-card class="shadow border">
                <h3 class="text-lg font-semibold mb-4">Select Client</h3>
                <x-mary-select label="Client"
                               wire:model="client_id"
                               :options="$clients"
                               option-label="contact_name"
                               option-value="id"
                               placeholder="Choose a client"
                               required
                />
            </x-mary-card>

            {{-- Service Selection --}}
            <x-mary-card class="shadow border">
                <h3 class="text-lg font-semibold mb-4">Select Services</h3>

                @if($services->count() > 0)
                    <div class="space-y-4">
                        @foreach($services as $service)
                            <div class="border rounded-lg p-4 {{ in_array($service->id, $selected_services) ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox"
                                           wire:model.live="selected_services"
                                           value="{{ $service->id }}"
                                           class="mt-1 w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary"
                                    />
                                    <div class="flex-1">
                                        <div class="font-semibold text-lg">{{ $service->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            @if($service->pricing_type === 'bulk')
                                                Package Price: £{{ number_format($service->base_price, 2) }}
                                            @else
                                                Individual Pricing - Select items below
                                            @endif
                                        </div>

                                        {{-- Individual Pricing Items --}}
                                        @if($service->pricing_type === 'individual' && in_array($service->id, $selected_services))
                                            <div class="mt-3 space-y-2">
                                                <div class="text-sm font-medium">Select Items:</div>
                                                @foreach($service->items as $item)
                                                    <div class="flex items-center gap-2 ml-4">
                                                        <input type="checkbox"
                                                               wire:model.live="service_data.{{ $service->id }}.items.{{ $item->id }}"
                                                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                                                        />
                                                        <span class="text-sm">{{ $item->name }}</span>
                                                        <span class="text-sm text-gray-600">
                                                            @if($item->price !== null)
                                                                (£{{ number_format($item->price, 2) }})
                                                            @else
                                                                (Included)
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Service Fields --}}
                                        @if(in_array($service->id, $selected_services) && $service->fields->count() > 0)
                                            <div class="mt-3 space-y-2">
                                                <div class="text-sm font-medium">Service Details:</div>
                                                @foreach($service->fields as $field)
                                                    <div class="ml-4">
                                                        <label class="text-sm font-medium">{{ $field->field_label }}</label>
                                                        @if($field->field_type === 'text')
                                                            <input type="text"
                                                                   wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                   placeholder="{{ $field->field_label }}"
                                                            />
                                                        @elseif($field->field_type === 'number')
                                                            <input type="number"
                                                                   wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                   placeholder="{{ $field->field_label }}"
                                                            />
                                                         @elseif($field->field_type === 'select')
                                                             <select wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}"
                                                                     class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                             >
                                                                 <option value="">Select {{ $field->field_label }}</option>
                                                                 @if(!empty($field->options) && is_array($field->options))
                                                                     @foreach($field->options as $option)
                                                                         @if(!empty(trim($option)))
                                                                             <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                                                         @endif
                                                                     @endforeach
                                                                 @endif
                                                             </select>
                                                         @elseif($field->field_type === 'date')
                                                             <div class="grid grid-cols-2 gap-2">
                                                                 <div>
                                                                     <label class="text-xs text-gray-600">Start Date</label>
                                                                     <input type="date"
                                                                            wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}.start_date"
                                                                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                     />
                                                                 </div>
                                                                 <div>
                                                                     <label class="text-xs text-gray-600">End Date</label>
                                                                     <input type="date"
                                                                            wire:model="service_data.{{ $service->id }}.fields.{{ $field->id }}.end_date"
                                                                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                                     />
                                                                 </div>
                                                             </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
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
                        <x-mary-button type="button"
                                       class="btn-primary w-full"
                                       wire:click="saveProposal"
                                       :disabled="!$client_id || count($selected_services) === 0"
                        >
                            Create Proposal
                        </x-mary-button>
                    </div>
                </div>
            </x-mary-card>
        </div>
    </div>
</div>
