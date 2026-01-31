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

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'selected_services' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->selected_services = [];
        $this->service_data = [];
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

            if ($service) {
                if ($service->pricing_type === 'bulk') {
                    // Bulk pricing - use base price
                    $this->total_price += $service->base_price;
                } else {
                    // Individual pricing - sum of selected items
                    if (isset($this->service_data[$serviceId]['items'])) {
                        foreach ($this->service_data[$serviceId]['items'] as $itemId => $selected) {
                            if ($selected) {
                                $item = $service->items()->find($itemId);
                                if ($item && $item->price !== null) {
                                    $this->total_price += $item->price;
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

        $proposal = Proposal::create([
            'client_id' => $this->client_id,
            'total_price' => $this->total_price,
            'status' => 'draft',
        ]);

        foreach ($this->selected_services as $serviceId) {
            $service = Service::find($serviceId);

            if ($service) {
                $price = 0;

                if ($service->pricing_type === 'bulk') {
                    $price = $service->base_price;
                } else {
                    if (isset($this->service_data[$serviceId]['items'])) {
                        foreach ($this->service_data[$serviceId]['items'] as $itemId => $selected) {
                            if ($selected) {
                                $item = $service->items()->find($itemId);
                                if ($item && $item->price !== null) {
                                    $price += $item->price;
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
        }

        $this->success('Proposal', 'Created successfully');

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
