<?php

namespace App\Helpers\Plugins;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function sendVerificationCodeMail($email): void
    {
        try
        {
            $mailVerification = EmailVerification::create([
                'email'=>$email,
                'code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
                'status'=>0,
            ]);

            Mail::to($email)->send(new VerificationCodeMail($mailVerification));
        }
        catch (\Exception $exception)
        {
            \Log::error("Verification mail not send: {$exception->getMessage()}");
        }
    }
}
