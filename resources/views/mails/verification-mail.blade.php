@component('mail::message')
    # Email Verification

    Your verification code is **{{ $emailVerification->code ?? '' }}**

    Enter the above code in the verification step to login your account.
@endcomponent
