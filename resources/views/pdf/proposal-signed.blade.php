@php
    $person = $proposal->client->persons->first();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Proposal #{{ $proposal->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .status {
            display: inline-block;
            padding: 5px 15px;
            background: #10B981;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 10px;
        }

        .client-info {
            margin-bottom: 30px;
        }

        .client-info h3 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .client-info table {
            width: 100%;
        }

        .client-info td {
            padding: 4px 0;
        }

        .client-info td:first-child {
            font-weight: bold;
            width: 150px;
        }

        table.services {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.services th,
        table.services td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table.services th {
            background: #f5f5f5;
            font-weight: bold;
        }

        table.services td:nth-child(2),
        table.services td:nth-child(3),
        table.services td:nth-child(4) {
            text-align: right;
        }

        table.services th:nth-child(2),
        table.services th:nth-child(3),
        table.services th:nth-child(4) {
            text-align: right;
        }

        .totals {
            float: right;
            width: 300px;
            margin-bottom: 40px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 8px 10px;
        }

        .totals td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .totals .grand-total {
            background: #333;
            color: white;
            font-size: 16px;
        }

        .signature-section {
            clear: both;
            padding-top: 50px;
            border-top: 2px solid #333;
            margin-top: 20px;
        }

        .signature-block {
            width: 300px;
            display: inline-block;
            margin-right: 100px;
        }

        .signature-block h4 {
            margin-bottom: 15px;
        }

        .signature-image {
            max-width: 250px;
            max-height: 80px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 1px;
            margin: 30px 0 5px 0;
        }

        .signature-label {
            font-size: 10px;
            color: #666;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .contents-section {
            margin-bottom: 30px;
        }

        .contents-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .contents-section p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PROPOSAL</h1>
        <p>Proposal #{{ $proposal->id }}</p>
        <div class="status">SIGNED & APPROVED</div>
    </div>

    <div class="client-info">
        <h3>Client Information</h3>
        <table>
            <tr>
                <td>Company:</td>
                <td>{{ $proposal->client->company_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Contact:</td>
                <td>{{ $person ? $person->first_name . ' ' . $person->last_name : 'N/A' }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $person->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>{{ $person->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>
                    {{ $proposal->client->address ?? '' }}
                    @if ($proposal->client->city)
                        {{ $proposal->client->city }}
                    @endif
                    @if ($proposal->client->postcode)
                        {{ $proposal->client->postcode }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>{{ $proposal->created_at->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td>Signed Date:</td>
                <td>{{ optional($proposal->signed_at)->format('M d, Y H:i') }}</td>

            </tr>
        </table>
    </div>

    @if ($proposal->contents && $proposal->contents->count() > 0)
        <div class="contents-section">
            <h3>Proposal Content</h3>
            @foreach ($proposal->contents as $content)
                @if ($content->title)
                    <h4>{{ $content->title }}</h4>
                @endif
                <div>{!! $proposal->renderContent($content) !!}</div>
            @endforeach
        </div>
    @endif

    @if ($proposal->services && $proposal->services->count() > 0)

        @php $grandTotal = 0; @endphp

        <table class="services">
            <thead>
                <tr>
                    <th style="width: 70%;">Service</th>
                    <th style="width: 30%; text-align:right;">Amount</th>
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

                    {{-- INDIVIDUAL PRICING --}}
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
                                    <td style="text-align:right;">
                                        £{{ number_format($price, 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        {{-- BULK SERVICE --}}
                        @php
                            $serviceTotal = $proposalService->price;
                        @endphp

                        <tr>
                            <td style="padding-left:20px;">
                                Fixed Service Fee
                            </td>
                            <td style="text-align:right;">
                                £{{ number_format($serviceTotal, 2) }}
                            </td>
                        </tr>
                    @endif

                    {{-- SUBTOTAL --}}
                    <tr>
                        <td><strong>{{ $serviceModel->name }} Subtotal</strong></td>
                        <td style="text-align:right;">
                            <strong>£{{ number_format($serviceTotal, 2) }}</strong>
                        </td>
                    </tr>

                    @php $grandTotal += $serviceTotal; @endphp

                    {{-- SPACE ROW --}}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                @endforeach

                {{-- GRAND TOTAL --}}
                <tr style="background:#333; color:#fff;">
                    <td><strong>GRAND TOTAL</strong></td>
                    <td style="text-align:right;">
                        <strong>£{{ number_format($grandTotal, 2) }}</strong>
                    </td>
                </tr>

            </tbody>
        </table>

    @endif


    <div class="signature-section">
        <div class="signature-block">
            <h4>Client Signature</h4>
            @if ($proposal->signature_image)
                <img src="{{ $proposal->signature_image }}" alt="Client Signature" class="signature-image">
            @endif
            <div class="signature-line"></div>
            <div class="signature-label">{{ $proposal->client->contact_name ?? 'Client' }}</div>
            <div class="signature-label">{{ optional($proposal->signed_at)->format('M d, Y') }}</div>
        </div>

        <div class="signature-block">
            <h4>Authorized Signatory</h4>
            <div class="signature-line"></div>
            <div class="signature-label">Company Representative</div>
            <div class="signature-label">{{ $proposal->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This proposal was electronically generated and signed. All parties agree to be bound by the terms outlined
            above.</p>
    </div>
</body>

</html>
