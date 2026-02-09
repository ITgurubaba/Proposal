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
            <x-mary-card class="shadow border">
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
            <x-mary-card class="shadow border">
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
                                                    @if ($field->field_type === 'date' && is_array($value))
                                                        {{-- Date Range Display --}}
                                                        @if (isset($value['start_date']) || isset($value['end_date']))
                                                            <div class="text-sm col-span-2">
                                                                <span
                                                                    class="text-gray-600">{{ $field->field_label }}:</span>
                                                                <span class="font-medium ml-1">
                                                                    @if (isset($value['start_date']) && !empty($value['start_date']))
                                                                        {{ \Carbon\Carbon::parse($value['start_date'])->format('M d, Y') }}
                                                                    @else
                                                                        Not set
                                                                    @endif
                                                                    to
                                                                    @if (isset($value['end_date']) && !empty($value['end_date']))
                                                                        {{ \Carbon\Carbon::parse($value['end_date'])->format('M d, Y') }}
                                                                    @else
                                                                        Not set
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @elseif(!empty($value))
                                                        {{-- Regular Field Display --}}
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

            {{-- Proposal Contents --}}
            @if ($proposal->contents->count() > 0)
                <x-mary-card class="shadow border">
                    <h3 class="text-lg font-semibold mb-4">Proposal Contents</h3>
                    <div class="space-y-4">
                        @foreach ($proposal->contents as $content)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-semibold text-lg">{{ $content->title }}</div>
                                    @if ($content->content_type === 'service')
                                        <x-mary-badge value="Service" class="badge-info" />
                                    @else
                                        <x-mary-badge value="Sub-Serive" class="badge-secondary" />
                                    @endif
                                </div>
                                @if ($content->service)
                                    <div class="text-sm text-gray-500 mb-2">Service: {{ $content->service->name }}
                                    </div>
                                @endif
                                @if ($content->service_item)
                                    <div class="text-sm text-gray-500 mb-2">Item: {{ $content->service_item->name }}
                                    </div>
                                @endif
                                <div class="text-sm text-gray-600 prose prose-sm max-w-none">
                                    {!! $this->renderProcessedContent($content) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-mary-card>
            @endif
        </div>

        {{-- Right Column - Summary & Actions --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Status Card --}}
            <x-mary-card class="shadow border">
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
                            Send Proposal
                        </x-mary-button>
                    @elseif($proposal->status === 'sent')
                        <x-mary-button type="button" class="btn-success w-full" wire:click="markAsAccepted">
                            Mark as Accepted
                        </x-mary-button>
                        <x-mary-button type="button" class="btn-error w-full" wire:click="markAsRejected">
                            Mark as Rejected
                        </x-mary-button>
                    @endif
                </div>
            </x-mary-card>

            {{-- Total Price Card --}}
            <x-mary-card class="shadow border">
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
        </div>
    </div>
</div>
