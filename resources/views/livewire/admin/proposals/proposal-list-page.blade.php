<div>
    <x-mary-header subtitle="Manage and track all proposals.">

        <x-slot:title class="text-4xl">
            Proposals
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass" placeholder="Search..." type="search"
                wire:model.debounce.500ms="search" />
        </x-slot:middle>

        <x-slot:actions>
            <x-mary-button icon="o-plus" class="btn-primary btn-square" href="{{ route('admin::proposals:create') }}"
                wire:navigate />
        </x-slot:actions>

    </x-mary-header>

    <x-mary-card class="shadow border">
        <x-mary-table :headers="$headers" :rows="$data" :sort-by="$sortBy" with-pagination show-empty-text
            per-page="perPage" :per-page-values="[10, 15, 25, 50, 100]">
            @scope('cell_id', $item)
                <strong>{{ $item->id }}</strong>
            @endscope

            @scope('cell_signed', $item)
                @if ($item->isSigned())
                    <x-mary-icon name="o-check-circle" class="text-green-500 w-6 h-6" />
                @else
                    <x-mary-icon name="o-minus-circle" class="text-gray-300 w-6 h-6" />
                @endif
            @endscope

            @scope('cell_client_name', $item)
                <div>
                    <div class="font-semibold">{{ $item->client->company_name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $item->client->company_registration_number ?? '' }}</div>
                </div>
            @endscope

            @scope('cell_total_price', $item)
                <span class="font-semibold text-lg">£{{ number_format($item->total_price, 2) }}</span>
            @endscope

            @scope('cell_status', $item)
                @if ($item->status === 'draft')
                    <span class="btn btn-sm btn-warning">
                        Draft
                    </span>
                @elseif($item->status === 'sent')
                    <span class="btn btn-sm btn-primary">
                        Sent
                    </span>
                @elseif($item->status === 'accepted')
                    <span class="btn btn-sm btn-info">
                        Accepted
                    </span>
                @elseif($item->status === 'rejected')
                    <span class="btn btn-sm btn-error">
                        Rejected
                    </span>
                @elseif($item->status === 'approved')
                    <span class="btn btn-sm btn-success">
                        Approved
                    </span>
                @endif
            @endscope



            @scope('cell_created_at', $item)
                {{ $item->created_at->format('M d, Y') }}
            @endscope

            @scope('actions', $item)
                <div class="flex gap-3">

                    <x-mary-button icon="o-eye" class="btn-sm btn-info btn-circle" tooltip="View"
                        href="{{ route('admin::proposals:view', ['proposal_id' => $item->id]) }}" wire:navigate />

                    {{-- ✅ Edit only if Draft or Rejected --}}
                    @if (in_array($item->status, ['draft', 'rejected']))
                        <x-mary-button icon="o-pencil" class="btn-sm btn-warning btn-circle" tooltip="Edit"
                            href="{{ route('admin::proposals:edit', ['proposal_id' => $item->id]) }}" wire:navigate />
                    @endif

                    {{-- ✅ Send Proposal Link Button --}}
                    @if (in_array($item->status, ['sent', 'accepted']) && !$item->isSigned())
                        <x-mary-button icon="o-paper-airplane" class="btn-sm btn-success btn-circle"
                            tooltip="Send Signing Link" wire:click="openSendLinkModal({{ $item->id }})" />
                    @endif

                    {{-- ✅ View Signed PDF --}}
                    @if ($item->isSigned() && $item->signed_pdf_path)
                            <a href="{{ route('admin::proposals:download-signed', $item->id) }}"
                            class="btn btn-sm btn-primary"
                            target="_blank">
                                Download
                            </a>
                        @endif


                    <div x-data="{
                        confirmDelete(id) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Once deleted, you will not be able to recover this record!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $wire.destroy(id);
                                }
                            });
                        }
                    }">
                        <x-mary-button icon="o-trash" tooltip="Delete" class="btn-sm btn-error text-white btn-circle"
                            @click="confirmDelete({{ $item->id }})" />
                    </div>

                </div>
            @endscope

        </x-mary-table>
    </x-mary-card>

    {{-- Send Proposal Link Modal --}}
    <x-mary-modal title="Send Proposal Signing Link" wire:model="showSendLinkModal" class="backdrop-blur">
        @php($proposalId = $selectedProposalId ?? 0)
        <div class="space-y-4" x-data="{ proposalId: {{ $proposalId }} }">
            <p class="text-gray-600">
                Share this secure link with your client to sign the proposal:
            </p>
            <div class="flex gap-2">
                <x-mary-input readonly x-model="proposalId" x-bind:value="'/proposal/sign/' + proposalId"
                    class="flex-1" />
                <x-mary-button icon="o-clipboard" class="btn-primary"
                    @click="navigator.clipboard.writeText('/proposal/sign/' + proposalId); $dispatch('notify', { message: 'Link copied!' })" />
            </div>
            <p class="text-sm text-gray-500">
                Or send directly via email:
            </p>
            @if ($selectedClientEmail)
                <div class="bg-gray-100 p-3 rounded text-sm">
                    <strong>Email will be sent to:</strong><br>
                    <span class="text-primary font-semibold">
                        {{ $selectedClientEmail }}
                    </span>
                </div>
            @endif

            <x-mary-button label="Send Email to Client" icon="o-paper-airplane" class="btn-primary w-full"
                wire:click="sendProposalEmail" />
        </div>
        <x-slot:actions>
            <x-mary-button label="Close" @click="$wire.showSendLinkModal = false" />
        </x-slot:actions>
    </x-mary-modal>
</div>
