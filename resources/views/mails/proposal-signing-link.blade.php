<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proposal Ready for Signature</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:40px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#1f2937; padding:20px; text-align:center;">
                        <h2 style="color:#ffffff; margin:0;">Guru Accountancy</h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px;">

                        <h3 style="margin-top:0;">Proposal Ready for Signature</h3>

                        <p>Dear <strong>{{ $contactName }}</strong>,</p>

                        <p>
                            We have prepared a proposal for your review and signature.
                            Please find the details below.
                        </p>

                        <!-- Proposal Box -->
                        <table width="100%" cellpadding="10" cellspacing="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; margin:20px 0;">
                            <tr>
                                <td><strong>Proposal ID:</strong></td>
                                <td>#{{ $proposal->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Company:</strong></td>
                                <td>{{ $proposal->client->company_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Price:</strong></td>
                                <td>£{{ number_format($proposal->total_price, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created Date:</strong></td>
                                <td>{{ $proposal->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>

                        <p>
                            Please review and sign your proposal securely using the button below:
                        </p>

                        <!-- Button -->
                        <div style="text-align:center; margin:30px 0;">
                            <a href="{{ $signingUrl }}"
                               style="background:#2563eb; color:#ffffff; padding:14px 28px; text-decoration:none; border-radius:6px; font-weight:bold; display:inline-block;">
                                Review & Sign Proposal
                            </a>
                        </div>

                        <p style="font-size:14px; color:#555;">
                            This secure link will allow you to review the complete proposal and sign electronically.
                            <br><br>
                            <strong>Note:</strong> This link will expire in 30 days.
                        </p>

                        <hr style="border:none; border-top:1px solid #e5e7eb; margin:30px 0;">

                        <p style="font-size:13px; color:#777;">
                            If the button above does not work, copy and paste the link below into your browser:
                            <br><br>
                            <a href="{{ $signingUrl }}" style="color:#2563eb;">{{ $signingUrl }}</a>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#888;">
                        © {{ date('Y') }} Guru Accountancy. All rights reserved.
                        <br>
                        This is an automated email. Please do not reply directly.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
