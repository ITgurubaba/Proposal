<?php

namespace App\Livewire\Admin\Services;

use App\Helpers\Traits\WithMaryTable;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ServiceListPage extends Component
{
    use WithPagination, WithMaryTable, Toast;
    public function mount()
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'name', 'label' => 'Name','sortable' => false],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
        ];
    }
    public function render()
    {

        $data = Service::query();

        if (checkData($this->search))
        {
            $data->where(function ($q) {
                $q->orWhere('id', 'like', $this->search)
                    ->orWhere('name', 'like', "{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%");
            });
        }

        $data = $data->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        return view('livewire.admin.services.service-list-page',compact('data'));
    }

    public function destroy($id = null):void
    {
        $check = Service::find($id);

        if ($check)
        {
            $check->delete();
            $this->success('Service','Deleted successfully');
        }
    }
}
