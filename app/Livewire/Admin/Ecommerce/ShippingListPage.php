<?php

namespace App\Livewire\Admin\Ecommerce;

use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Support\Arr;
use Livewire\Component;
use Mary\Traits\Toast;

class ShippingListPage extends Component
{
    use Toast;

    public $countries = [];

    public $request = [];
    public $editRequest = [];

    public $selectedTaxId;

    protected $validationAttributes = [
        'request.country_id'=>'country',
        'request.name'=>'name',
        'request.rate'=>'rate',
        'request.status'=>'status',
    ];

    protected function rules($edit = false):array
    {
        if ($edit)
        {
            return [
                'editRequest.country_id'=>'required',
                'editRequest.rate'=>'required|numeric|min:0',
                'editRequest.status'=>'required',
            ];
        }

        return [
            'request.country_id'=>'required',
            'request.rate'=>'required|numeric|min:0',
            'request.status'=>'required',
        ];
    }

    public function mount():void
    {
        $this->countries = Country::where('status',1)->get()->toArray();

        $this->countries = Arr::collapse([
            [
                [
                    'id'=>0,
                    'nicename'=>'All Countries',
                ]
            ],
            $this->countries,
        ]);

        $this->NewRequest();
    }

    public function render()
    {
        $data = ShippingCharge::orderBy('id','asc')->get();

        return view('livewire.admin.ecommerce.shipping-list-page',compact('data'));
    }

    public function Submit():void
    {
        $this->validate();
        $this->create($this->request);
    }

    public function create($data = []):void
    {
        try
        {
            ShippingCharge::create($data);
            $this->NewRequest();
            $this->success('Shipping Rate','Added Successfully');
        }
        catch (\Exception $exception)
        {
            $this->error('Error!',$exception->getMessage());
        }
    }

    public function Save():void
    {
        $this->validate($this->rules(true));
        $this->update($this->editRequest);
    }

    public function update($data = []):void
    {
        try
        {
            ShippingCharge::where('id',$this->selectedTaxId)->update($data);
            $this->selectedTaxId = null;
            $this->success('Shipping Rate','Updated Successfully');
        }
        catch (\Exception $exception)
        {
            $this->error('Error!',$exception->getMessage());
        }
    }

    public function EditRequest($id = null):void
    {
        $check = ShippingCharge::find($id);
        if ($check)
        {
            $this->selectedTaxId = $check->id;
            $this->editRequest = $check->only([
                'country_id',
                'type',
                'name',
                'description',
                'rate',
                'status',
            ]);
        }
    }

    public function NewRequest():void
    {
        $this->request = [
            'country_id'=>0,
            'type'=>'percentage',
            'name'=>null,
            'description'=>null,
            'rate'=>0,
            'status'=>1,
        ];
    }

    public function destroy($id = null)
    {
        $check = ShippingCharge::find($id);

        if ($check)
        {
            $check->delete();
            $this->success('Shipping','Deleted successfully');
        }
    }
}
