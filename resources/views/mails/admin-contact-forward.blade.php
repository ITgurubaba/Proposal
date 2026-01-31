<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        /* Base */
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; background-color: #ffffff; margin: 20px auto; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #2d3e50; font-size: 20px; margin: 0 0 10px; }
        h3 { color: #2d3e50; font-size: 18px; margin: 20px 0 10px; }
        p  { color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 12px; }

        /* Info panel */
        .info-box { background-color: #f0f4f8; border-left: 4px solid #007bff; padding: 12px 15px; margin-top: 15px; border-radius: 5px; font-size: 14px; color: #374151; }
        .info-box strong { color: #1f2937; }

        /* Key/Value rows */
        .kv { margin: 6px 0; }
        .k  { display: inline-block; min-width: 160px; color: #1f2937; font-weight: bold; }
        .v  { color: #374151; }

        /* Badge */
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background:#e8f0fe; color:#1a56db; font-size:12px; font-weight:600; vertical-align: middle; }

        /* Button */
        .btn-wrap { margin-top: 22px; text-align: center; }
        .btn {
            display: inline-block; background-color: #007bff; color: #ffffff !important;
            padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600;
        }

        /* Footer */
        .footer { margin-top: 24px; font-size: 13px; color: #9ca3af; text-align: center; }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container { padding: 15px !important; border-radius: 0 !important; }
            h2 { font-size: 18px !important; }
            p, .info-box { font-size: 15px !important; }
            .k { min-width: 120px !important; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📝 New Contact Form Submission <span class="badge">{{ config('app.name') }}</span></h2>
    <p>You have received a new message from the <strong>Contact Us</strong> page on <strong>{{ config('app.name') }}</strong>.</p>

    <!-- Sender -->
    <div class="info-box">
        <div class="kv"><span class="k">Name:</span> <span class="v">{{ ($data['first_name'] ?? '') }} {{ ($data['last_name'] ?? '') }}</span></div>
        <div class="kv"><span class="k">Email:</span> <span class="v">{{ $data['email'] ?? 'N/A' }}</span></div>
        <div class="kv"><span class="k">Phone:</span> <span class="v">{{ $data['phone'] ?? 'N/A' }}</span></div>
    </div>

    <!-- Message -->
    <h3>Message Details</h3>
    <div class="info-box">
        <div class="kv"><span class="k">Subject:</span> <span class="v">{{ $data['subject'] ?? 'N/A' }}</span></div>
        <div class="kv"><span class="k">Message:</span>
            <span class="v">{!! nl2br(e($data['user_message'] ?? $data['message'] ?? '')) !!}</span>
        </div>
    </div>

    <!-- CTA -->
    <div class="btn-wrap">
        <a href="{{ rtrim(config('app.url'), '/') . '/admin' }}" class="btn">Open Website</a>
    </div>

    <p class="footer">{{ config('app.name') }} &copy; {{ date('Y') }}. All rights reserved.</p>
</div>
</body>
</html>
