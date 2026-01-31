<?php

namespace App\Jobs;

use App\Mail\AdminContactForwardMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminContactForwardMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;
    public array $emails;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, array $emails)
    {
        $this->data = $data;
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send the admin notification mail
        Mail::to($this->emails)->send(new AdminContactForwardMail($this->data));
    }
}
