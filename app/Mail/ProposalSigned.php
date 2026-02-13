<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSigned extends Mailable
{
    use Queueable, SerializesModels;

    public Proposal $proposal;

    public ?string $pdfContent;

    public string $pdfName;

    public function __construct(Proposal $proposal, ?string $pdfContent = null, string $pdfName = '')
    {
        $this->proposal = $proposal;
        $this->pdfContent = $pdfContent;
        $this->pdfName = $pdfName ?: "proposal-{$proposal->id}-signed.pdf";
    }

    public function build(): self
    {
        $this->subject('Proposal Signed - ' . ($this->proposal->client->company_name ?? 'Your Proposal'));

        $message = $this->view('emails.proposal-signed')
            ->with(['proposal' => $this->proposal]);

        // Add attachment if PDF content is available
        if ($this->pdfContent) {
            $message->attachData($this->pdfContent, $this->pdfName, [
                'mime' => 'application/pdf',
            ]);
        }

        return $message;
    }
}
