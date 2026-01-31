<?php

namespace App\Jobs;

use App\Mail\ContactThankYouMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactThankYouMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;
    public string $email;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, string $email)
    {
        $this->data = $data;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send the thank you mail to the visitor
        Mail::to($this->email)->send(new ContactThankYouMail($this->data));
    }
}
