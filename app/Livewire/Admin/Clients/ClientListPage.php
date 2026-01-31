<?php

namespace App\Livewire\Admin\Clients;

use App\Helpers\Traits\WithMaryTable;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ClientListPage extends Component
{
    use WithPagination,
        WithMaryTable,
        Toast;

    public function mount()
    {
        $this->initTableFilter('admin.table.company.clients.filters');

        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1', 'sortable' => true],
            ['key' => 'contact_name', 'label' => 'Contact Name', 'sortable' => true],
            ['key' => 'customer_type', 'label' => 'Customer Type', 'sortable' => true],
            ['key' => 'company_registration_number', 'label' => 'Company Reg No', 'sortable' => true],
            ['key' => 'city', 'label' => 'City', 'sortable' => true],
            ['key' => 'country', 'label' => 'Country', 'sortable' => true],
        ];
    }

    public function render()
    {
        $data = Client::query();

        if (checkData($this->search)) {
            $data->where(function ($q) {
                $q->orWhere('id', 'like', $this->search)
                    ->orWhere('contact_name', 'like', "{$this->search}%")
                    ->orWhere('contact_name', 'like', "%{$this->search}%")
                    ->orWhere('company_registration_number', 'like', "%{$this->search}%");
            });
        }

        $data = $data->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        return view('livewire.admin.clients.client-list-page', compact('data'));
    }

    public function destroy($id = null)
    {
        $check = Client::find($id);

        if ($check) {
            $check->persons()->delete();
            $check->delete();
            $this->success('Client', 'Deleted successfully');
        }
    }

    public function getCustomerTypeLabel($type): string
    {
        $types = [
            'limited_company' => 'Ltd / Limited Company',
            'limited_by_share' => 'Limited By Share',
            'limited_by_guarantee' => 'Limited By Guarantee',
            'cic' => 'CIC',
            'partnership' => 'Partnership',
            'llp' => 'LLP',
            'individual' => 'Individual',
        ];

        return $types[$type] ?? $type;
    }
}
