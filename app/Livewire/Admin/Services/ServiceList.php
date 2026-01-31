<?php

namespace App\Livewire\Admin\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;

class ServiceList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = ['column' => 'id', 'direction' => 'desc'];

    protected $queryString = ['search', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getHeadersProperty()
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function getDataProperty()
    {
        return Service::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    // Delete
    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('success', 'Service deleted successfully');
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->status = !$service->status;
        $service->save();
        session()->flash('success', 'Service status updated successfully');
    }

    public function render()
    {
        return view('livewire.admin.services.service-list', [
            'headers' => $this->headers,
            'data' => $this->data,
        ]);
    }
}
