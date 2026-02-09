<?php

namespace App\Livewire\Admin\Proposals;

use App\Models\Client;
use App\Models\OtherContent;
use App\Models\Proposal;
use App\Models\ProposalContent;
use App\Models\ProposalService;
use App\Models\Service;
use App\Models\ServiceContent;
use Livewire\Component;
use Mary\Traits\Toast;

class ProposalCreatePage extends Component
{
    use Toast;

    /* =======================
        BASIC STATE
    ======================= */
    public $client_id;
    public $proposal_id;
    public $selected_services = [];
    public $service_data = [];
    public $total_price = 0;

    /* =======================
        CONTENT STATE (IMPORTANT)
    ======================= */
    public $selected_service_contents = [];
    public $selected_other_contents = [];
    public $edited_contents = [];

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'selected_services' => 'required|array|min:1',
    ];

    /* =======================
        MOUNT
    ======================= */
    public function mount($proposal_id = null)
    {
        $this->proposal_id = $proposal_id;

        if ($proposal_id) {
            $proposal = Proposal::with(['services', 'contents'])->findOrFail($proposal_id);

            if (!in_array($proposal->status, ['draft', 'rejected'])) {
                abort(403, 'Only draft or rejected proposals can be edited.');
            }

            $this->client_id = $proposal->client_id;
            $this->total_price = $proposal->total_price;

            foreach ($proposal->services as $ps) {
                $this->selected_services[] = $ps->service_id;
                $this->service_data[$ps->service_id] = $ps->data ?? [];
            }

            // Load proposal contents
            foreach ($proposal->contents as $content) {
                if ($content->type === 'service') {
                    $this->selected_service_contents[] = $content->content_id;
                    $this->edited_contents['service_' . $content->content_id] = $content->content;
                }

                if ($content->type === 'other') {
                    $this->selected_other_contents[] = $content->content_id;
                    $this->edited_contents['other_' . $content->content_id] = $content->content;
                }
            }
        }
    }

    /* =======================
        COMPUTED PROPERTIES
    ======================= */

    public function getActiveServicesProperty()
    {
        return Service::where('status', true)
            ->with(['items.contents', 'fields', 'contents'])
            ->get();
    }

    public function getClientsProperty()
    {
        return Client::all();
    }

    // ✅ FIX FOR $availableServiceContents
    public function getAvailableServiceContentsProperty()
    {
        if (empty($this->selected_services)) {
            return collect();
        }

        return ServiceContent::whereIn('service_id', $this->selected_services)->get();
    }

    // ✅ FIX FOR $availableOtherContents
    public function getAvailableOtherContentsProperty()
    {
        return OtherContent::where('status', true)->get();
    }

    /* =======================
        PRICE CALCULATION
    ======================= */

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
    }

    public function updatedServiceData()
    {
        $this->calculateTotal();
    }

    public function getProcessedServiceContent($contentId)
    {
        $content = ServiceContent::find($contentId);

        if (!$content) {
            return '';
        }

        $serviceId = $content->service_id;

        $rawContent = $this->edited_contents['service_' . $contentId]
            ?? $content->content;

        return $this->replaceServicePlaceholders($rawContent, $serviceId);
    }

    public function getProcessedAnyServiceContent(ServiceContent $content)
    {
        $raw = $this->edited_contents['service_' . $content->id]
            ?? $content->content;

        return $this->replaceServicePlaceholders($raw, $content->service_id);
    }

    public function replaceServicePlaceholders(string $content, int $serviceId): string
    {
        if (!isset($this->service_data[$serviceId]['fields'])) {
            return $content;
        }

        foreach ($this->service_data[$serviceId]['fields'] as $fieldId => $value) {

            $field = \App\Models\ServiceField::find($fieldId);
            if (!$field) continue;

            $placeholder = '[' . $field->field_name . ']';

            $content = str_replace(
                $placeholder,
                (string) $value,
                $content
            );
        }

        return $content;
    }


    public function updatedSelectedOtherContents($value)
    {
        foreach ($this->selected_other_contents as $contentId) {

            $key = 'other_' . $contentId;

            if (!isset($this->edited_contents[$key])) {
                $content = OtherContent::find($contentId);
                $this->edited_contents[$key] = $content?->content ?? '';
            }
        }
    }

    public function updatedSelectedServiceContents()
    {
        foreach ($this->selected_service_contents as $contentId) {

            $key = 'service_' . $contentId;

            if (!isset($this->edited_contents[$key])) {
                $content = ServiceContent::find($contentId);
                $this->edited_contents[$key] = $content?->content ?? '';
            }
        }
    }


    public function calculateTotal()
    {
        $this->total_price = 0;

        foreach ($this->selected_services as $serviceId) {
            $service = Service::find($serviceId);
            if (!$service) continue;

            if ($service->pricing_type === 'bulk') {
                $this->total_price += !empty($this->service_data[$serviceId]['custom_price'])
                    ? (float) $this->service_data[$serviceId]['custom_price']
                    : (float) $service->base_price;
            } else {
                if (!empty($this->service_data[$serviceId]['items'])) {
                    foreach ($this->service_data[$serviceId]['items'] as $itemId => $selected) {
                        if ($selected) {
                            $this->total_price += !empty($this->service_data[$serviceId]['item_prices'][$itemId])
                                ? (float) $this->service_data[$serviceId]['item_prices'][$itemId]
                                : (float) optional($service->items()->find($itemId))->price;
                        }
                    }
                }
            }
        }
    }
    public function editServiceContent($contentId)
    {
        // open editor
        if (!in_array($contentId, $this->selected_service_contents)) {
            $this->selected_service_contents[] = $contentId;
        }

        // initialise content if not set
        $key = 'service_' . $contentId;

        if (!isset($this->edited_contents[$key])) {
            $content = ServiceContent::find($contentId);
            $this->edited_contents[$key] = $content?->content ?? '';
        }
    }

    /* =======================
        SAVE PROPOSAL
    ======================= */

    public function saveProposal()
    {
        $this->validate();

        if ($this->proposal_id) {
            $proposal = Proposal::findOrFail($this->proposal_id);
            $proposal->update([
                'client_id' => $this->client_id,
                'total_price' => $this->total_price,
            ]);

            ProposalService::where('proposal_id', $proposal->id)->delete();
            ProposalContent::where('proposal_id', $proposal->id)->delete();
        } else {
            $proposal = Proposal::create([
                'client_id' => $this->client_id,
                'total_price' => $this->total_price,
                'status' => 'draft',
            ]);
        }

        /* SERVICES */
        foreach ($this->selected_services as $serviceId) {
            ProposalService::create([
                'proposal_id' => $proposal->id,
                'service_id' => $serviceId,
                'price' => 0,
                'data' => $this->service_data[$serviceId] ?? [],
            ]);
        }


        /* SERVICE CONTENTS */
        foreach ($this->selected_service_contents as $contentId) {

            $source = ServiceContent::find($contentId);
            if (!$source) continue;

            ProposalContent::create([
                'proposal_id'        => $proposal->id,
                'content_type'       => $source->service_item_id ? 'service_item' : 'service',
                'source_content_id'  => $source->id,
                'title'              => $source->title,
                'content'            => $this->edited_contents['service_' . $contentId] ?? $source->content,
                'service_id'         => $source->service_id,
                'service_item_id'    => $source->service_item_id,
                'sort_order'         => 0,
            ]);
        }


        /* OTHER CONTENTS */
        foreach ($this->selected_other_contents as $contentId) {

            $source = OtherContent::find($contentId);
            if (!$source) continue;

            ProposalContent::create([
                'proposal_id'        => $proposal->id,
                'content_type'       => 'other',
                'source_content_id'  => $source->id,
                'title'              => $source->title,
                'content'            => $this->edited_contents['other_' . $contentId] ?? $source->content,
                'sort_order'         => 0,
            ]);
        }


        $this->success(
            'Proposal',
            $this->proposal_id ? 'Updated successfully' : 'Created successfully'
        );

        return redirect()->route('admin::proposals:list');
    }

    /* =======================
        RENDER
    ======================= */
    public function render()
    {
        return view('livewire.admin.proposals.proposal-create-page', [
            'services' => $this->active_services,
            'clients'  => $this->clients,
            'availableOtherContents' => $this->availableOtherContents,
            'availableServiceContents' => $this->availableServiceContents,
        ]);
    }
}
