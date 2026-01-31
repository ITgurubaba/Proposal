<?php

namespace App\Livewire\Admin\Ecommerce;

use App\Helpers\Traits\WithMaryTable;
use App\Models\VatTax;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TaxListPage extends Component
{
    use WithPagination, WithMaryTable, Toast;

    public $editModal = false;

    public $request = [];

    protected $validationAttributes = [
        'request.name' => 'name',
        'request.rate' => 'rate',
    ];

    protected function rules():array
    {
        return [
            'request.name' => 'required|max:255',
            'request.rate' => 'required',
        ];
    }

    public function mount():void
    {
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'name', 'label' => 'Name','sortable' => true],
            ['key' => 'rate', 'label' => 'Rate','sortable' => false],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
        ];

        $this->NewRequest();
    }

    public function render()
    {
        $data = VatTax::query();

        if(checkData($this->search))
        {
            $data = $data->where('name', 'like', '%'.$this->search.'%');
        }

        $data = $data->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        return view('livewire.admin.ecommerce.tax-list-page',compact('data'));
    }

    public function Submit():void
    {
        $this->validate();
        $this->createTax($this->request);
    }

    private function createTax($data = []):void
    {
        try
        {
            VatTax::create($data);

            $this->editModal = false;
            $this->NewRequest();
            $this->success('New Tax','Created successfully');
        }
        catch (\Exception $e)
        {
            $this->error('Error!',$e->getMessage());
        }
    }

    public function Save():void
    {
        $this->validate();
        $this->updateTax($this->request);
    }

    public function updateTax($data = []):void
    {
        try
        {
            $check = VatTax::find($data['id']);
            $check->fill(\Arr::except($this->request,['id']));
            $check->save();

            $this->editModal = false;
            $this->NewRequest();
            $this->success('Edit Tax','Updated successfully');
        }
        catch (\Exception $e)
        {
            $this->error('Error!',$e->getMessage());
        }
    }

    public function openAddEditModal($id = null):void
    {
        if(checkData($id))
        {
            $check = VatTax::find($id);
            if($check)
            {
                $this->EditRequest($check);
                $this->editModal = true;
            }
        }
        else
        {
            $this->NewRequest();
            $this->editModal = true;
        }
    }

    private function EditRequest($check):void
    {
        $this->request = $check->only([
            'id',
            'type',
            'name',
            'description',
            'rate',
            'status',
        ]);
    }

    private function NewRequest():void
    {
        $this->request = [
            'type'=>'percentage',
            'name'=>null,
            'description'=>null,
            'rate'=>0,
            'status'=>1,
        ];
    }

    public function destroy($id = null)
    {
        $check = VatTax::find($id);

        if ($check)
        {
            $check->delete();
            $this->success('Vat Tax','Deleted successfully');
        }
    }

}
