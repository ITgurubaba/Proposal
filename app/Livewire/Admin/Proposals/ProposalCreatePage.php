<?php

namespace App\Livewire\Admin\Proposals;

use App\Models\Client;
use App\Models\Proposal;
use App\Models\ProposalService;
use App\Models\Service;
use Livewire\Component;
use Mary\Traits\Toast;

class ProposalCreatePage extends Component
{
    use Toast;

    public $client_id;
    public $selected_services = [];
    public $service_data = [];
    public $total_price = 0;
    public $proposal_id;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'selected_services' => 'required|array|min:1',
    ];

    public function mount($proposal_id = null)
    {
        $this->proposal_id = $proposal_id;

        if ($proposal_id) {
            $proposal = Proposal::with('services')->findOrFail($proposal_id);

            // ❌ sent / accepted edit na ho
            if (!in_array($proposal->status, ['draft', 'rejected'])) {
                abort(403, 'Only draft or rejected proposals can be edited.');
            }

            $this->client_id = $proposal->client_id;
            $this->total_price = $proposal->total_price;

            foreach ($proposal->services as $ps) {
                $this->selected_services[] = $ps->service_id;
                $this->service_data[$ps->service_id] = $ps->data ?? [];
            }
        } else {
            $this->selected_services = [];
            $this->service_data = [];
        }
    }


    public function getActiveServicesProperty()
    {
        return Service::where('status', true)->with(['items', 'fields'])->get();
    }

    public function getClientsProperty()
    {
        return Client::all();
    }

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
    }

    public function updatedServiceData()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_price = 0;

        foreach ($this->selected_services as $serviceId) {
            $service = Service::find($serviceId);
            if (!$service) continue;

            // ✅ BULK
            if ($service->pricing_type === 'bulk') {
                if (!empty($this->service_data[$serviceId]['custom_price'])) {
                    $this->total_price += (float)$this->service_data[$serviceId]['custom_price'];
                } else {
                    $this->total_price += (float)$service->base_price;
                }
            }

            // ✅ INDIVIDUAL
            else {
                if (isset($this->service_data[$serviceId]['items'])) {
                    foreach ($this->service_data[$serviceId]['items'] as $itemId => $selected) {
                        if ($selected) {
                            if (!empty($this->service_data[$serviceId]['item_prices'][$itemId])) {
                                $this->total_price += (float)$this->service_data[$serviceId]['item_prices'][$itemId];
                            } else {
                                $item = $service->items()->find($itemId);
                                if ($item && $item->price !== null) {
                                    $this->total_price += (float)$item->price;
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public function saveProposal()
    {
        $this->validate();

        if ($this->proposal_id) {
            // UPDATE MODE
            $proposal = Proposal::findOrFail($this->proposal_id);

            $proposal->update([
                'client_id' => $this->client_id,
                'total_price' => $this->total_price,
            ]);

            // old services delete
            ProposalService::where('proposal_id', $proposal->id)->delete();
        } else {
            // CREATE MODE
            $proposal = Proposal::create([
                'client_id' => $this->client_id,
                'total_price' => $this->total_price,
                'status' => 'draft',
            ]);
        }

        foreach ($this->selected_services as $serviceId) {
            $service = Service::find($serviceId);
            if (!$service) continue;

            $price = 0;

            if ($service->pricing_type === 'bulk') {

                $price = !empty($this->service_data[$serviceId]['custom_price'])
                    ? (float) $this->service_data[$serviceId]['custom_price']
                    : (float) $service->base_price;
            } else {

                if (isset($this->service_data[$serviceId]['items'])) {
                    foreach ($this->service_data[$serviceId]['items'] as $itemId => $selected) {
                        if ($selected) {

                            if (!empty($this->service_data[$serviceId]['item_prices'][$itemId])) {
                                $price += (float) $this->service_data[$serviceId]['item_prices'][$itemId];
                            } else {
                                $item = $service->items()->find($itemId);
                                if ($item && $item->price !== null) {
                                    $price += (float) $item->price;
                                }
                            }
                        }
                    }
                }
            }



            ProposalService::create([
                'proposal_id' => $proposal->id,
                'service_id' => $serviceId,
                'price' => $price,
                'data' => $this->service_data[$serviceId] ?? [],
            ]);
        }

        $this->success('Proposal', $this->proposal_id ? 'Updated successfully' : 'Created successfully');

        return redirect()->route('admin::proposals:list');
    }


    public function render()
    {
        return view('livewire.admin.proposals.proposal-create-page', [
            'services' => $this->active_services,
            'clients' => $this->clients,
        ]);
    }
}
