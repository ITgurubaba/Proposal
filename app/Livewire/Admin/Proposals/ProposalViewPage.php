<?php

namespace App\Livewire\Admin\Proposals;

use App\Models\Proposal;
use App\Models\ServiceField;

use Livewire\Component;
use Mary\Traits\Toast;

class ProposalViewPage extends Component
{
    use Toast;

    public $proposal;
    public $proposal_id;


    public function mount($proposal_id)
    {
        $this->proposal_id = $proposal_id;
        $this->proposal = Proposal::with(['client', 'services.service', 'services.service.items', 'services.service.fields', 'contents'])->findOrFail($proposal_id);
    }


    public function sendProposal()
    {
        $this->proposal->status = 'sent';
        $this->proposal->save();

        $this->success('Proposal', 'Sent successfully');
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

    public function renderProcessedContent($content)
    {
        // Only service / service_item content
        if (!$content->service_id) {
            return $content->content;
        }

        // find proposal service
        $proposalService = $this->proposal->services
            ->firstWhere('service_id', $content->service_id);

        if (!$proposalService || empty($proposalService->data['fields'])) {
            return $content->content;
        }

        $html = $content->content;

        foreach ($proposalService->data['fields'] as $fieldId => $value) {
            $field = ServiceField::find($fieldId);
            if (!$field) continue;

            $html = str_replace(
                '[' . $field->field_name . ']',
                is_array($value) ? implode(' - ', $value) : $value,
                $html
            );
        }

        return $html;
    }
    public function render()
    {
        return view('livewire.admin.proposals.proposal-view-page');
    }
}
