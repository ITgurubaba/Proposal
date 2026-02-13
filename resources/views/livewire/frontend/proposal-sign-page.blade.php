<div class="container py-5">
    <div class="mx-auto" style="max-width: 900px;">

        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="fw-bold">Proposal Signing</h1>
            <p class="text-muted">Please review and sign the proposal below</p>
        </div>

        <!-- Proposal Card -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Proposal #{{ $proposal->id }}</h5>

                <span
                    class="badge 
                    @if ($proposal->status === 'sent') bg-warning text-dark
                    @elseif($proposal->status === 'approved') bg-success
                    @elseif($proposal->status === 'rejected') bg-danger
                    @else bg-secondary @endif
                ">
                    {{ ucfirst($proposal->status) }}
                </span>
            </div>

            <div class="card-body">

                <!-- Client Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Client</h6>
                        <p class="mb-1 fw-bold">{{ $proposal->client->company_name ?? 'N/A' }}</p>
                        <p class="mb-1">{{ $clientName }}</p>
                        <p class="mb-1">{{ $clientEmail }}</p>
                    </div>

                    <div class="col-md-6 text-md-end">
                        <h6 class="text-muted">Total Price</h6>
                        <h4 class="fw-bold">£{{ number_format($proposal->total_price, 2) }}</h4>
                        <small class="text-muted">
                            Created: {{ $proposal->created_at->format('M d, Y') }}
                        </small>
                    </div>
                </div>

                <!-- Services -->
                @if ($proposal->services && $proposal->services->count() > 0)

                    @php $grandTotal = 0; @endphp

                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Services</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($proposal->services as $proposalService)
                                        @php
                                            $serviceModel = $proposalService->service;
                                            $serviceTotal = 0;
                                            $data = $proposalService->data ?? [];
                                        @endphp

                                        {{-- SERVICE NAME --}}
                                        <tr>
                                            <td><strong>{{ $serviceModel->name }}</strong></td>
                                            <td></td>
                                        </tr>

                                        {{-- INDIVIDUAL --}}
                                        @if ($serviceModel->pricing_type === 'individual' && !empty($data['items']))
                                            @foreach ($data['items'] as $itemId => $selected)
                                                @if ($selected)
                                                    @php
                                                        $item = $serviceModel->items->firstWhere('id', $itemId);
                                                        $price = $data['item_prices'][$itemId] ?? ($item->price ?? 0);
                                                        $serviceTotal += $price;
                                                    @endphp

                                                    <tr>
                                                        <td style="padding-left:20px;">
                                                            {{ $item->name }}
                                                        </td>
                                                        <td class="text-end">
                                                            £{{ number_format($price, 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- BULK --}}
                                            @php
                                                $serviceTotal = $proposalService->price;
                                            @endphp

                                            <tr>
                                                <td style="padding-left:20px;">
                                                    Fixed Service Fee
                                                </td>
                                                <td class="text-end">
                                                    £{{ number_format($serviceTotal, 2) }}
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- SUBTOTAL --}}
                                        <tr class="fw-bold">
                                            <td>{{ $serviceModel->name }} Subtotal</td>
                                            <td class="text-end">
                                                £{{ number_format($serviceTotal, 2) }}
                                            </td>
                                        </tr>

                                        @php $grandTotal += $serviceTotal; @endphp
                                    @endforeach

                                    {{-- GRAND TOTAL --}}
                                    <tr class="table-dark fw-bold">
                                        <td>GRAND TOTAL</td>
                                        <td class="text-end">
                                            £{{ number_format($grandTotal, 2) }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                @endif


                <!-- Proposal Content -->
                @if ($proposal->contents && $proposal->contents->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Proposal Details</h6>
                        @foreach ($proposal->contents as $content)
                            @if ($content->title)
                                <h6 class="fw-semibold mt-3">{{ $content->title }}</h6>
                            @endif
                            <div class="text-muted">
                                {!! $proposal->renderContent($content) !!}
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>


        {{-- ===================================================== --}}
        {{-- SENT STATE --}}
        {{-- ===================================================== --}}
       @if ($proposal->status === 'sent')


            <!-- Action Card -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">

                    @if (!$showSignature)
                        <h5 class="mb-3">Do you approve this proposal?</h5>

                        <div class="d-flex justify-content-center gap-3">

                            <button wire:click="acceptProposal" class="btn btn-success">
                                Accept Proposal
                            </button>

                            <button wire:click="rejectProposal" class="btn btn-outline-danger"
                                onclick="return confirm('Are you sure you want to reject this proposal?')">
                                Reject Proposal
                            </button>

                        </div>
                    @endif

                </div>
            </div>

            @if ($showSignature)

                <!-- Signature Section -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Sign Proposal</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="submitSignature">

                            <div class="mb-3">
                                <label class="form-label">Your Signature</label>
                                <livewire:frontend.components.signature-pad wire:model="signatureData"
                                    canvas-id="signature-canvas" wire:key="signature-{{ $proposal->id }}" />

                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    Sign & Final Approve
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            @endif

        @endif



        {{-- ===================================================== --}}
        {{-- APPROVED STATE --}}
        {{-- ===================================================== --}}
        @if ($proposal->status === 'approved')

            <div class="alert alert-success text-center mb-4">
                <strong>Proposal Approved & Signed</strong><br>
                Signed on {{ $proposal->signed_at->format('M d, Y H:i') }}
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Client Signature</h5>
                </div>
                <div class="card-body text-center">
                    @if ($proposal->signature_image)
                        <img src="{{ $proposal->signature_image }}" class="img-fluid" style="max-width:400px;">
                    @endif
                </div>
            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- REJECTED STATE --}}
        {{-- ===================================================== --}}
        @if ($proposal->status === 'rejected')
            <div class="alert alert-danger text-center mb-4">
                <strong>Proposal Rejected</strong><br>
                This proposal has been rejected.
            </div>
        @endif


        <!-- Footer -->
        <div class="text-center text-muted small">
            Need help? Contact us at support@example.com
        </div>

    </div>
</div>
