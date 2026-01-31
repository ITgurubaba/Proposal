<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\RoroQuoteMail;
use Mail;

class SendRoroQuoteMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;
    public string $to;

    public function __construct(array $data, string $to)
    {
        $this->data = $data;
        $this->to = $to;
    }

    public function handle(): void
    {
        Mail::to($this->to)->send(new RoroQuoteMail($this->data));
    }
}
