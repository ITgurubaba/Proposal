@component('mail::message')
# Proposal Signed Successfully

Dear {{ $proposal->client->contact_name ?? 'Client' }},

Thank you for signing the proposal. Your signature has been recorded and the proposal is now approved.

## Proposal Details

- **Proposal ID:** #{{ $proposal->id }}
- **Company:** {{ $proposal->client->company_name ?? 'N/A' }}
- **Total Price:** £{{ number_format($proposal->total_price, 2) }}
- **Signed Date:** {{ $proposal->signed_at->format('M d, Y H:i') }}

Please find the signed proposal attached to this email for your records.

If you have any questions, please don't hesitate to contact us.

Thank you for your business!

@component('mail::subcopy')
This is an automated message. Please do not reply directly to this email.
@endcomponent
@endcomponent
