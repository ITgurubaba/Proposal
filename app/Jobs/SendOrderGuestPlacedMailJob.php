<?php

namespace App\Jobs;

use App\Mail\OrderGuestPlacedMail;
use App\Models\OrderGuest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderGuestPlacedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderGuest;

    /**
     * Create a new job instance.
     */
    public function __construct(OrderGuest $orderGuest)
    {
        $this->orderGuest = $orderGuest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->orderGuest->email)->send(new OrderGuestPlacedMail($this->orderGuest));
    }
}
