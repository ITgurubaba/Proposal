<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSigningLink extends Mailable
{
    use Queueable, SerializesModels;

    public Proposal $proposal;
    public string $signingUrl;
    public ?string $contactName;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
        $this->signingUrl = route(
            'frontend.proposal.sign',
            $proposal
        );

        // Get contact name from client's first person
        $this->contactName = 'Client';
        if ($proposal->client && $proposal->client->persons->isNotEmpty()) {
            $person = $proposal->client->persons->first();
            $this->contactName = trim($person->first_name . ' ' . $person->last_name);
        } elseif ($proposal->client) {
            $this->contactName = $proposal->client->company_name ?? 'Client';
        }
    }

   public function build()
{
    return $this->subject('Proposal Ready for Signature')
        ->html(view('mails.proposal-signing-link', [
            'proposal'    => $this->proposal,
            'contactName' => $this->contactName,
            'signingUrl'  => $this->signingUrl,
        ])->render());
}

}
