<?php

namespace App\Livewire\Admin\Proposals;

use App\Helpers\Traits\WithMaryTable;
use App\Models\Proposal;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ProposalListPage extends Component
{
    use WithPagination, WithMaryTable, Toast;

    public function mount()
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1', 'sortable' => true],
            ['key' => 'client_name', 'label' => 'Client', 'sortable' => false],
            ['key' => 'total_price', 'label' => 'Total Price', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ];
    }

    public function render()
    {
        $data = Proposal::with('client');

        if (checkData($this->search)) {
            $data->where(function ($q) {
                $q->orWhere('id', 'like', $this->search)
                    ->orWhereHas('client', function ($query) {
                        $query->where('contact_name', 'like', "%{$this->search}%");
                    });
            });
        }

        $data = $data->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        return view('livewire.admin.proposals.proposal-list-page', compact('data'));
    }

    public function destroy($id = null): void
    {
        $check = Proposal::find($id);

        if ($check) {
            $check->delete();
            $this->success('Proposal', 'Deleted successfully');
        }
    }
}
