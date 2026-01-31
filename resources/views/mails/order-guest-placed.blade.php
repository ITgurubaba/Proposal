<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        strong {
            font-weight: bold;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }

        .order-summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .order-summary p {
            margin: 8px 0;
        }

        .item-details {
            padding-left: 20px;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            table {
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            p,
            li {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <h2>Hi {{ $order->fullName() }},</h2>
                <p>Thank you for your order! Here’s a quick summary:</p>

                <div class="order-summary">
                    <p><strong>Order Total (With Tax):</strong> {{ currency($order->total) }}</p>
                    <p><strong>Order Date:</strong> {{ $order->order_date }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                </div>

                <h3>Items Ordered:</h3>
                <ul class="item-details">
                    @foreach ($items as $item)
                        <li>
                            {{ $item->name }}
                            @if (!empty($item->color) || !empty($item->size))
                                (
                                @if (!empty($item->color))
                                    Color: {{ $item->color }}
                                @endif
                                @if (!empty($item->color) && !empty($item->size))
                                    -
                                @endif
                                @if (!empty($item->size))
                                    Size: {{ $item->size }}
                                @endif
                                )
                            @endif
                            ({{ $item->quantity }}x) - {{ currency($item->total) }}
                        </li>
                    @endforeach



                </ul>

                <p>We’ll reach out once your order ships. Feel free to reply to this email if you have any questions.
                </p>

                <p class="footer">Thanks,<br />Boxpack Logistics Team</p>
            </td>
        </tr>
    </table>
</body>

</html>
