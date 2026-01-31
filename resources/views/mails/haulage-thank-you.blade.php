<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2d3e50;
            font-size: 20px;
        }

        p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
        }

        .info-box {
            background-color: #f0f4f8;
            border-left: 4px solid #007bff;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #9ca3af;
            text-align: center;
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 15px !important;
                border-radius: 0 !important;
            }

            h2 {
                font-size: 18px !important;
            }

            p, .info-box {
                font-size: 15px !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hi {{ $name }},</h2>
        <p>Thank you for requesting a haulage quote with Boxpack Logistics. We’ve received your request and will get back to you shortly with more information.</p>

        <div class="info-box">
            <strong>Your submitted details:</strong><br>
            <strong>Container Size:</strong> {{ $cont_size }}<br>
            <strong>Load Type:</strong> {{ $ld_type }}<br>
            <strong>Pickup Address:</strong> {{ $pickup_address }}<br>
            <strong>Dropoff Address:</strong> {{ $dropoff_address }}<br>
            <strong>Loading Date:</strong> {{ $loading_date }}<br>
            <strong>Phone:</strong> {{ $phone_country_code }} {{ $phone }}<br>
            <strong>Email:</strong> {{ $email }}<br>
            <strong>Additional Info:</strong> {{ $additional_info ?? 'N/A' }}
        </div>

        <p class="footer">Boxpack Logistics &copy; {{ date('Y') }}. All rights reserved.</p>
    </div>
</body>
</html>
