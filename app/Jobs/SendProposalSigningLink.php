<?php

namespace App\Jobs;

use App\Mail\ProposalSigningLink;
use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendProposalSigningLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $proposalId;

    public function __construct(int $proposalId)
    {
        $this->proposalId = $proposalId;
    }

    public function handle(): void
    {
        try {
            Log::info('SendProposalSigningLink: Starting job for proposal ID: ' . $this->proposalId);

            // Reload proposal from database
            $proposal = Proposal::with(['client.persons'])->find($this->proposalId);

            if (!$proposal) {
                Log::warning('SendProposalSigningLink: Proposal not found', [
                    'proposal_id' => $this->proposalId
                ]);
                return;
            }

            // Check if client exists
            if (!$proposal->client) {
                Log::warning('SendProposalSigningLink: Proposal has no client', [
                    'proposal_id' => $this->proposalId
                ]);
                return;
            }

            // Check if persons exist
            $persons = $proposal->client->persons;
            if ($persons->isEmpty()) {
                Log::warning('SendProposalSigningLink: Client has no persons', [
                    'client_id' => $proposal->client->id,
                    'proposal_id' => $this->proposalId
                ]);
                return;
            }

            $person = $persons->first();
            $clientEmail = $person->email ?? null;

            if (!$clientEmail) {
                Log::warning('SendProposalSigningLink: Person has no email', [
                    'person_id' => $person->id ?? null,
                    'proposal_id' => $this->proposalId
                ]);
                return;
            }

            Log::info('SendProposalSigningLink: Sending email to: ' . $clientEmail);

            Mail::to($clientEmail)->send(
                new ProposalSigningLink($proposal)
            );

            Log::info('SendProposalSigningLink: Email sent successfully for proposal ID: ' . $this->proposalId);
        } catch (\Exception $e) {
            Log::error('SendProposalSigningLink job failed', [
                'proposal_id' => $this->proposalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
