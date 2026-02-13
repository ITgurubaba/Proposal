<?php

namespace App\Livewire\Frontend;

use App\Jobs\GenerateSignedProposalPdf;
use App\Jobs\SendSignedProposalEmail;
use App\Models\Proposal;
use Livewire\Component;
use Mary\Traits\Toast;

class ProposalSignPage extends Component
{
    use Toast;

    public Proposal $proposal;

    public ?string $signatureData = null;

    public bool $isSigned = false;

    public bool $isLoading = false;

    public string $clientName = '';

    public string $clientEmail = '';

    public bool $showSignature = false;


    protected $listeners = [
        'signatureSaved' => 'handleSignatureSaved',
    ];

    public function mount(Proposal $proposal): void
    {
        $this->proposal = Proposal::with([
            'client.persons',
            'services',
            'services.service',
            'contents'
        ])->findOrFail($proposal->id);


        if ($this->proposal->isSigned()) {
            $this->isSigned = true;
        }
        $person = $this->proposal->client->persons->first();

        $this->clientName = $person
            ? $person->first_name . ' ' . $person->last_name
            : '';

        $this->clientEmail = $person->email ?? '';
    }


    public function render()
    {
        return view('livewire.frontend.proposal-sign-page', [
            'proposal' => $this->proposal,
        ]);
    }

    public function rejectProposal(): void
    {
        if (!in_array($this->proposal->status, ['sent'])) {
            return;
        }

        $this->proposal->update([
            'status' => 'rejected'
        ]);

        $this->proposal->refresh();

        $this->showSignature = false;

        $this->success('Proposal rejected successfully.');
    }

    public function acceptProposal(): void
    {
        if ($this->proposal->status !== 'sent') {
            return;
        }

        // Sirf signature show karo
        $this->showSignature = true;
    }




    /**
     * Handle signature saved from component
     */
    public function handleSignatureSaved(string $signature): void
    {
        $this->signatureData = $signature;
    }

    /**
     * Submit the signed proposal
     */
    public function submitSignature(): void
    {
        $this->validate([
            'signatureData' => 'required',
        ]);

        $this->isLoading = true;

        try {
            // Update proposal with signature
            $this->proposal->update([
                'signature_image' => $this->signatureData,
                'signed_at' => now(),
                'status' => 'approved',
            ]);

            // Dispatch PDF generation job
            GenerateSignedProposalPdf::dispatch($this->proposal);

            // Dispatch email job
            SendSignedProposalEmail::dispatch($this->proposal);

            $this->isSigned = true;

            $this->success('Proposal signed successfully! A confirmation email has been sent.');
        } catch (\Exception $e) {
            $this->error('Failed to sign proposal. Please try again.');
            report($e);
        } finally {
            $this->isLoading = false;
        }
    }
}
