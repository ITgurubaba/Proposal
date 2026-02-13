<?php

namespace App\Jobs;

use App\Mail\ProposalSigned;
use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendSignedProposalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Proposal $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function handle(): void
    {
        // Load relationships
       
        $this->proposal->load(['client.persons']);

        $clientEmail = $this->proposal->client->email ?? null;

        if (!$clientEmail) {
            return;
        }

        // Get PDF path
        $pdfPath = $this->proposal->signed_pdf_path;

        if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
            $pdfContent = Storage::disk('public')->get($pdfPath);
            $pdfName = "proposal-{$this->proposal->id}-signed.pdf";

            // Send email with attachment
            Mail::to($clientEmail)->send(
                new ProposalSigned($this->proposal, $pdfContent, $pdfName)
            );
        } else {
            // Send email without attachment if PDF not ready
            Mail::to($clientEmail)->send(
                new ProposalSigned($this->proposal)
            );
        }
    }
}
