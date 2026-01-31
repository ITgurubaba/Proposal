<?php

namespace App\Livewire\Admin\Grouping;

use App\Helpers\Admin\CurrencyHelper;
use App\Helpers\Traits\WithMaryTable;
use App\Models\Currency;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CurrencyListPage extends Component
{
    use WithPagination,
        WithMaryTable,
        Toast;

    protected $validationAttributes = [
        'request.name'=>'name',
        'request.code'=>'code',
        'request.symbol'=>'symbol',
        'request.format'=>'format',
        'request.exchange_rate'=>'exchange rate',
        'request.active'=>'active',
    ];

    public array $request = [];
    public $editModal = false;

    public function mount()
    {
        $this->initTableFilter('admin.table.grouping.currency.filters');

        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'name', 'label' => 'Name','sortable' => true],
            ['key' => 'code', 'label' => 'Code','sortable' => true],
            ['key' => 'symbol', 'label' => 'Symbol','sortable' => false],
            ['key' => 'format', 'label' => 'Format','sortable' => false],
            ['key' => 'exchange_rate', 'label' => 'Exchange Rate','sortable' => false],
            ['key' => 'active', 'label' => 'Status','sortable' => true],
        ];

        $this->NewRequest();

    }

    public function render()
    {
        $data = Currency::query();

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

        return view('livewire.admin.grouping.currency-list-page',compact('data'));
    }

    public function save():void
    {
        if(Arr::has($this->request,'id'))
        {
            $this->validate([
                'request.name'=>'required|max:255',
                'request.code'=>'required|max:255',
                'request.symbol'=>'required|max:255',
                'request.format'=>'required|max:255',
                'request.exchange_rate'=>'required|max:255',
                'request.active'=>'required'
            ]);
            $message = "updated successfully";
        }
        else
        {
            $this->validate([
                'request.name'=>'required|max:255',
                'request.code'=>'required|max:255',
                'request.symbol'=>'required|max:255',
                'request.format'=>'required|max:255',
                'request.exchange_rate'=>'required|max:255',
                'request.active'=>'required'
            ]);
            $message = "added successfully";
        }

        Currency::createOrUpdate($this->request);

        $this->success('Currency',$message);
        $this->editModal = false;
    }

    public function OpenAddEditModal($id = null):void
    {
        $occupation = Currency::find($id);
        if($occupation)
        {
            $this->EditRequest($occupation);
        }
        else{ $this->NewRequest(); }
        $this->editModal = true;
    }

    protected function EditRequest($occupation):void
    {
        $this->request = $occupation->only([
            'id',
            'name',
            'code',
            'symbol',
            'format',
            'exchange_rate',
            'active',
        ]);
    }

    protected function NewRequest():void
    {
        $this->request = [
            'name'=>null,
            'code'=>null,
            'symbol'=>null,
            'format'=>null,
            'exchange_rate'=>null,
            'active'=>1,
        ];
    }

    public function destroy($id):void
    {
        $check = Currency::find($id);
        if($check)
        {
            $check->delete();
            $this->success(
                'Currency',
                'Deleted successfully',
            );
        }
    }


    public function SyncCurrencyRates():void
    {
        CurrencyHelper::getExchangeRate();
        $this->success('Currency','Exchange rates updated successfully');
    }
}
