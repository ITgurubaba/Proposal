<div>
    <x-mary-header subtitle="View proposal details and quotation.">

        <x-slot:title class="text-4xl">
            Proposal #{{ $proposal->id }}
        </x-slot:title>

        <x-slot:actions>
            <x-mary-button icon="o-arrow-left" class="btn-secondary" href="{{ route('admin::proposals:list') }}"
                wire:navigate />
        </x-slot:actions>

    </x-mary-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column - Proposal Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Client Information --}}
            <x-mary-card class="shadow border mb-4">
                <h3 class="text-lg font-semibold mb-4">Client Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Company Name:</div>
                        <div class="font-semibold">{{ $proposal->client->company_name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Company Registration:</div>
                        <div class="font-semibold">{{ $proposal->client->company_registration_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Address:</div>
                        <div class="font-semibold">
                            {{ $proposal->client->address_line_1 ?? '' }}
                            {{ $proposal->client->address_line_2 ? ', ' . $proposal->client->address_line_2 : '' }}
                            {{ $proposal->client->address_line_3 ? ', ' . $proposal->client->address_line_3 : '' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">City & Zip:</div>
                        <div class="font-semibold">
                            {{ $proposal->client->city ?? '' }}
                            {{ $proposal->client->zip_code ? ', ' . $proposal->client->zip_code : '' }}
                        </div>
                    </div>
                </div>
            </x-mary-card>

            {{-- Services --}}
            <x-mary-card class="shadow border mb-4">
                <h3 class="text-lg font-semibold mb-4">Selected Services</h3>

                @if ($proposal->services->count() > 0)
                    <div class="space-y-4">
                        @foreach ($proposal->services as $proposalService)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="font-semibold text-lg">{{ $proposalService->service->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            @if ($proposalService->service->pricing_type === 'bulk')
                                                Package Pricing
                                            @else
                                                Individual Pricing
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-xl font-bold text-primary">
                                        £{{ number_format($proposalService->price, 2) }}

                                    </div>
                                </div>

                                {{-- Selected Items for Individual Pricing --}}
                                @if ($proposalService->service->pricing_type === 'individual' && isset($proposalService->data['items']))
                                    <div class="mt-3">
                                        <div class="text-sm font-medium mb-2">Selected Items:</div>
                                        <div class="space-y-1">
                                            @foreach ($proposalService->data['items'] as $itemId => $selected)
                                                @if ($selected)
                                                    @php
                                                        $item = $proposalService->service->items->find($itemId);
                                                    @endphp
                                                    @if ($item)
                                                        <div class="flex justify-between text-sm ml-4">
                                                            <span>{{ $item->name }}</span>
                                                            <span>
                                                                @if ($item->price !== null)
                                                                    £{{ number_format($item->price, 2) }}
                                                                @else
                                                                    Included
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Service Fields Data --}}
                                @if (isset($proposalService->data['fields']) && count($proposalService->data['fields']) > 0)
                                    <div class="mt-3">
                                        <div class="text-sm font-medium mb-2">Service Details:</div>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach ($proposalService->data['fields'] as $fieldId => $value)
                                                @php
                                                    $field = $proposalService->service->fields->find($fieldId);
                                                @endphp

                                                @if ($field)
                                                    {{-- DATE RANGE --}}
                                                    @if ($field->field_type === 'date_range' && is_array($value))
                                                        <div class="text-sm col-span-2">
                                                            <span
                                                                class="text-gray-600">{{ $field->field_label }}:</span>
                                                            <span class="font-medium ml-1">
                                                                {{ $value['start'] ?? 'Not set' }}
                                                                to
                                                                {{ $value['end'] ?? 'Not set' }}
                                                            </span>
                                                        </div>

                                                        {{-- NORMAL FIELD --}}
                                                    @elseif(!is_array($value) && !empty($value))
                                                        <div class="text-sm">
                                                            <span
                                                                class="text-gray-600">{{ $field->field_label }}:</span>
                                                            <span class="font-medium ml-1">{{ $value }}</span>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                @endif

                                {{-- Service Content --}}
                                @php
                                    $serviceContents = $proposalService->service->contents->whereNull(
                                        'service_item_id',
                                    );
                                    $itemContents = [];
                                    if (
                                        $proposalService->service->pricing_type === 'individual' &&
                                        isset($proposalService->data['items'])
                                    ) {
                                        foreach ($proposalService->data['items'] as $itemId => $selected) {
                                            if ($selected) {
                                                $item = $proposalService->service->items->find($itemId);
                                                if ($item) {
                                                    $itemContents[$itemId] = $item->contents ?? collect();
                                                }
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
                                                <div class="mb-3">
                                                    <div class="font-semibold text-sm">{{ $content->title }}</div>
                                                    <div class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                        {!! $this->renderProcessedContent($content) !!}

                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- Item-level content for selected items --}}
                                        @if (!empty($itemContents))
                                            @foreach ($itemContents as $itemId => $contents)
                                                @if ($contents->count() > 0)
                                                    <div class="mb-3">
                                                        {{-- <div class="font-semibold text-sm text-primary">
                                                            {{ $proposalService->service->items->find($itemId)->name }}
                                                            Content:
                                                        </div> --}}
                                                        @foreach ($contents as $content)
                                                            <div class="mt-2">
                                                                <div class="font-medium text-sm">{{ $content->title }}
                                                                </div>
                                                                <div
                                                                    class="text-sm text-gray-600 mt-1 prose prose-sm max-w-none">
                                                                    {!! $this->renderProcessedContent($content) !!}

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        No services selected for this proposal.
                    </div>
                @endif
            </x-mary-card>
        </div>

        {{-- Right Column - Summary & Actions --}}
        <div class="lg:col-span-1 space-y-6 mb-4">
            {{-- Status Card --}}
            <x-mary-card class="shadow border mb-4">
                <h3 class="text-lg font-semibold mb-4">Proposal Status</h3>

                <div class="mb-4">
                    @switch($proposal->status)
                        @case('draft')
                            <x-mary-badge value="Draft" class="badge-secondary text-lg px-4 py-2" />
                        @break

                        @case('sent')
                            <x-mary-badge value="Sent" class="badge-primary text-lg px-4 py-2" />
                        @break

                        @case('accepted')
                            <x-mary-badge value="Accepted" class="badge-success text-lg px-4 py-2" />
                        @break

                        @case('rejected')
                            <x-mary-badge value="Rejected" class="badge-error text-lg px-4 py-2" />
                        @break
                    @endswitch
                </div>

                <div class="text-sm text-gray-600 mb-4">
                    Created: {{ $proposal->created_at->format('M d, Y H:i') }}
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-2">
                    @if ($proposal->status === 'draft')
                        <x-mary-button type="button" class="btn-primary w-full" wire:click="sendProposal">
                            Ready to Send Proposal
                        </x-mary-button>
                    @endif
                </div>
            </x-mary-card>

            {{-- Total Price Card --}}
            <x-mary-card class="shadow border mb-4">
                <h3 class="text-lg font-semibold mb-4">Quotation Summary</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Services:</span>
                        <span class="font-semibold">{{ $proposal->services->count() }}</span>
                    </div>

                    <div class="border-t pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-3xl font-bold text-primary">
                                £{{ number_format($proposal->total_price, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-mary-card>
            
            <x-mary-card class="shadow border mt-6">
                <h3 class="text-lg font-semibold mb-4">Content Align</h3>

                {{-- SERVICES --}}
                <div class="mb-6">
                    <div class="font-semibold mb-3">Services</div>

                    <ul x-data x-init="new Sortable($el, {
                        animation: 150,
                        onEnd: (event) => {
                            let order = Array.from($el.children)
                                .map(el => el.dataset.id);
                    
                            $wire.updateServiceOrder(order);
                        }
                    })" class="space-y-2">

                        @foreach ($proposal->services->sortBy('sort_order') as $service)
                            <li data-id="{{ $service->id }}"
                                class="p-3 bg-gray-100 rounded cursor-move shadow-sm">

                                {{ $service->service->name }}

                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- OTHER CONTENT --}}
                <div>
                    <div class="font-semibold mb-3">Other Contents</div>

                    <ul x-data x-init="new Sortable($el, {
                        animation: 150,
                        onEnd: (event) => {
                            let order = Array.from($el.children)
                                .map(el => el.dataset.id);
                    
                            $wire.updateOtherOrder(order);
                        }
                    })" class="space-y-2">

                        @foreach ($proposal->contents->where('content_type', 'other')->sortBy('sort_order') as $content)
                            <li data-id="{{ $content->id }}" class="p-3 bg-gray-100 rounded cursor-move shadow-sm">

                                {{ $content->title }}

                            </li>
                        @endforeach
                    </ul>
                </div>
            </x-mary-card>




            <x-mary-card class="shadow border mt-6">
                <h3 class="text-lg font-semibold mb-4">PDF Preview</h3>

                <div class="border p-4 bg-white text-black"
                    style="font-family: Arial; line-height:1.6; 
                    max-height: 600px; 
                    overflow-y: auto;">
                    {{-- LOGO --}}
                        <div class="mb-4">
                            <img 
                                src="{{ asset(config('settings.admin_logo','assets/default/logo/flow.png')) }}"
                                alt="Company Logo"
                                style="max-height: 80px; margin: 0 auto;"
                            >
                        </div>
                    {{-- HEADER --}}
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold">PROPOSAL</h2>
                        {{-- <p>Proposal #{{ $proposal->id }}</p> --}}
                        <p>Date: {{ $proposal->created_at->format('M d, Y') }}</p>
                    </div>

                    {{-- CLIENT INFO --}}
                    <div class="mb-8">
                        <h4 class="font-bold mb-2 border-b pb-1">Client Information</h4>
                        <p><strong>Company:</strong> {{ $proposal->client->company_name }}</p>
                        <p><strong>Registration:</strong> {{ $proposal->client->company_registration_number }}</p>
                        <p>
                            <strong>Address:</strong>
                            {{ $proposal->client->address_line_1 }}
                            {{ $proposal->client->address_line_2 }}
                            {{ $proposal->client->address_line_3 }},
                            {{ $proposal->client->city }},
                            {{ $proposal->client->zip_code }}
                        </p>
                    </div>

                    {{-- SERVICES --}}
                    <div class="mb-8">
                        <h4 class="font-bold mb-4 border-b pb-1">Selected Services</h4>

                        @foreach ($proposal->services->sortBy('sort_order') as $proposalService)


                            <div class="mb-6">

                                {{-- SERVICE TITLE --}}
                                <div class="flex justify-between mb-2">
                                    <div class="font-semibold text-lg">
                                        {{ $proposalService->service->name }}
                                    </div>
                                    <div class="font-bold">
                                        £{{ number_format($proposalService->price, 2) }}
                                    </div>
                                </div>

                                {{-- SELECTED ITEMS --}}
                                @if ($proposalService->service->pricing_type === 'individual' && isset($proposalService->data['items']))
                                    <div class="ml-4 mb-3">
                                        <div class="font-semibold">Selected Items:</div>
                                        @foreach ($proposalService->data['items'] as $itemId => $selected)
                                            @if ($selected)
                                                @php
                                                    $item = $proposalService->service->items->find($itemId);
                                                @endphp
                                                @if ($item)
                                                    <div class="flex justify-between text-sm">
                                                        <span>{{ $item->name }}</span>
                                                        <span>
                                                            @if ($item->price !== null)
                                                                £{{ number_format($item->price, 2) }}
                                                            @else
                                                                Included
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- SERVICE FIELDS --}}
                                @if (isset($proposalService->data['fields']) && count($proposalService->data['fields']) > 0)
                                    <div class="ml-4 mb-3">
                                        <div class="font-semibold">Service Details:</div>

                                        @foreach ($proposalService->data['fields'] as $fieldId => $value)
                                            @php
                                                $field = $proposalService->service->fields->find($fieldId);
                                            @endphp

                                            @if ($field)
                                                @if ($field->field_type === 'date_range' && is_array($value))
                                                    <div class="text-sm">
                                                        {{ $field->field_label }}:
                                                        {{ $value['start'] ?? 'Not set' }}
                                                        to
                                                        {{ $value['end'] ?? 'Not set' }}
                                                    </div>
                                                @elseif(!is_array($value))
                                                    <div class="text-sm">
                                                        {{ $field->field_label }}: {{ $value }}
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- SERVICE CONTENT --}}
                                @php
                                    $serviceContents = $proposalService->service->contents->whereNull(
                                        'service_item_id',
                                    );
                                    $itemContents = [];

                                    if (
                                        $proposalService->service->pricing_type === 'individual' &&
                                        isset($proposalService->data['items'])
                                    ) {
                                        foreach ($proposalService->data['items'] as $itemId => $selected) {
                                            if ($selected) {
                                                $item = $proposalService->service->items->find($itemId);
                                                if ($item) {
                                                    $itemContents[$itemId] = $item->contents ?? collect();
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if ($serviceContents->count() > 0 || !empty($itemContents))
                                    <div class="ml-4 mt-3">
                                        <div class="font-semibold mb-2">Service Content:</div>

                                        {{-- SERVICE LEVEL CONTENT --}}
                                        @foreach ($serviceContents as $content)
                                            <div class="mb-2">
                                                <div class="font-semibold text-sm">{{ $content->title }}</div>
                                                <div class="text-sm">
                                                    {!! $this->renderProcessedContent($content) !!}
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- ITEM LEVEL CONTENT --}}
                                        @foreach ($itemContents as $contents)
                                            @foreach ($contents as $content)
                                                <div class="mb-2">
                                                    <div class="font-semibold text-sm">{{ $content->title }}</div>
                                                    <div class="text-sm">
                                                        {!! $this->renderProcessedContent($content) !!}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @endif

                            </div>

                        @endforeach
                    </div>

                    {{-- PROPOSAL EXTRA CONTENT --}}
                    @if ($proposal->contents->count() > 0)
                        <div class="mb-8">
                            <h4 class="font-bold mb-4 border-b pb-1">Additional Proposal Content</h4>

                            @foreach ($proposal->contents->sortBy('sort_order') as $content)
                                <div class="mb-4">
                                    <div class="font-semibold">{{ $content->title }}</div>
                                    <div class="text-sm">
                                        {!! $this->renderProcessedContent($content) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- TOTAL --}}
                    <div class="text-right text-xl font-bold border-t pt-4">
                        Total: £{{ number_format($proposal->total_price, 2) }}
                    </div>

                    <div class="mt-8 text-center text-sm text-gray-600">
                        Thank you for your business.
                    </div>

                </div>
            </x-mary-card>


        </div>
    </div>
</div>
