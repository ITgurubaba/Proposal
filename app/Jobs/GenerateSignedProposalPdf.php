<?php

namespace App\Jobs;

use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateSignedProposalPdf implements ShouldQueue
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
        $this->proposal->load([
            'client.persons',
            'services',
            'services.service',
            'contents'
        ]);


        // Generate PDF
        $pdf = Pdf::loadView('pdf.proposal-signed', [
            'proposal' => $this->proposal,
        ]);

        // Save PDF to storage
        $filename = "proposals/signed/proposal-{$this->proposal->id}-signed.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        // Update proposal with PDF path
        $this->proposal->update(['signed_pdf_path' => $filename]);
    }
}
