<?php

namespace App\Livewire\Admin\Proposals;

use App\Models\Proposal;
use App\Models\ProposalService;
use App\Models\ProposalContent;
use App\Models\ServiceField;
use Livewire\Component;
use Mary\Traits\Toast;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalViewPage extends Component
{
    use Toast;

    public $proposal;
    public $proposal_id;
    public $serviceOrder = [];
    public $otherContentOrder = [];


    public function mount($proposal_id)
    {
        $this->proposal_id = $proposal_id;

        $this->proposal = Proposal::with([
            'client',
            'services.service',
            'contents'
        ])->findOrFail($proposal_id);

        // Services order
        $this->serviceOrder = $this->proposal->services
            ->pluck('service_id')
            ->toArray();

        // Other content order
        $this->otherContentOrder = $this->proposal->contents
            ->where('content_type', 'other')
            ->sortBy('sort_order')
            ->pluck('id')
            ->toArray();
    }



    public function sendProposal()
    {
        $this->proposal->status = 'sent';
        $this->proposal->save();
        $this->success('Proposal', 'Sent successfully');
    }

  public function updateServiceOrder($order)
{
    foreach ($order as $index => $serviceId) {

        ProposalService::where('id', $serviceId)
            ->update(['sort_order' => $index]);
    }

    $this->proposal->refresh();
}


    public function updateOtherOrder($order)
    {
        foreach ($order as $index => $contentId) {
            ProposalContent::where('id', $contentId)
                ->update(['sort_order' => $index]);
        }

        $this->proposal->refresh();
    }


    public function markAsAccepted()
    {
        $this->proposal->status = 'accepted';
        $this->proposal->save();
        $this->success('Proposal', 'Marked as accepted');
    }

    public function markAsRejected()
    {
        $this->proposal->status = 'rejected';
        $this->proposal->save();
        $this->success('Proposal', 'Marked as rejected');
    }

    /* =====================================================
       CLIENT PLACEHOLDER REPLACEMENT
    ====================================================== */
    private function replaceClientPlaceholders(string $html): string
    {
        if (!$this->proposal->client) {
            return $html;
        }

        $client = $this->proposal->client;
        $person = $client->persons->first();

        $fullAddress = collect([
            $client->address_line_1,
            $client->address_line_2,
            $client->address_line_3,
            $client->city,
            $client->zip_code,
            $client->country,
        ])->filter()->implode(', ');

        $replacements = [
            '[company_name]'                => $client->company_name ?? '',
            '[company_registration_number]' => $client->company_registration_number ?? '',
            '[company_address]'             => $fullAddress,
            '[client_name]'                 => $person ? trim($person->first_name . ' ' . $person->last_name) : '',
            '[client_email]'                => $person->email ?? '',
            '[client_phone]'                => $person->phone ?? '',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $html
        );
    }

    /* =====================================================
       MAIN CONTENT PROCESSOR (SERVICE + OTHER)
    ====================================================== */
    public function renderProcessedContent($content)
    {
        $html = $content->content;

        // ✅ If service content, replace service fields
        if ($content->service_id) {

            $proposalService = $this->proposal->services
                ->firstWhere('service_id', $content->service_id);

            if ($proposalService && !empty($proposalService->data['fields'])) {

                foreach ($proposalService->data['fields'] as $fieldId => $value) {

                    $field = ServiceField::find($fieldId);
                    if (!$field) continue;

                    $html = str_replace(
                        '[' . $field->field_name . ']',
                        is_array($value) ? implode(' - ', $value) : $value,
                        $html
                    );
                }
            }
        }

        // ✅ ALWAYS replace client placeholders (even for Other content)
        $html = $this->replaceClientPlaceholders($html);

        return $html;
    }

    public function render()
    {
        return view('livewire.admin.proposals.proposal-view-page');
    }
}
