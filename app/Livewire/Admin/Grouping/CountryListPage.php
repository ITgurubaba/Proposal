<?php

namespace App\Livewire\Admin\Grouping;

use App\Helpers\Traits\WithMaryTable;
use App\Models\Country;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class CountryListPage extends Component
{
    use WithPagination,
        WithMaryTable,
        Toast;

    public array $request = [];
    public $editModal = false;

    protected array $validationAttributes = [
        'request.name' => 'name',
        'request.nicename' => 'nicename',
        'request.status' => 'status',
    ];

    public function mount()
    {
        $this->initTableFilter('admin.table.grouping.countries.filters');

        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'nicename', 'label' => 'Name','sortable' => true],
            ['key' => 'abbreviations', 'label' => 'Abbreviation','sortable' => false],
            ['key' => 'timezone', 'label' => 'Timezone','sortable' => false],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
        ];

        $this->NewRequest();

    }

    public function render()
    {
        $data = Country::query();

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

        return view('livewire.admin.grouping.country-list-page',compact('data'));
    }

    public function save()
    {
        $this->validate([
            'request.name'=>'required|max:255',
            'request.nicename'=>'required|max:255',
            'request.status'=>'required',
        ]);

        if (Country::where('id','!=',$this->request['id'] ??'0')->where('name',$this->request['name'])->count()>0)
        {
            $this->error('Error!','Country already exists');
        }
        else { $this->createOrUpdate($this->request); }
    }

    public function createOrUpdate($data):void
    {
        if (Arr::has($data,'id'))
        {
            Country::where('id',$data['id'])->update(Arr::only($data,['iso','name','nicename','timezone','status','abbreviations']));
            $this->editModal = false;
            $this->success('Country','Updated Successfully');
        }
        else {
            Country::create($data);
            $this->editModal = false;

            $this->success('Country','Created Successfully');
            $this->NewRequest();
        }
    }

    public function OpenAddEditModal($id = null):void
    {
        if (isset($id) && $id!=="")
        {
            $country = Country::find($id);
            $country?$this->EditRequest($country):$this->NewRequest();
        }
        else{ $this->NewRequest(); }
        $this->editModal = true;
    }

    protected function NewRequest():void
    {
        $this->request = [
            "iso" => null,
            "name" => null,
            "nicename" => null,
            "iso3" => null,
            "numcode" => null,
            "phonecode" => null,
            "region_id" => null,
            "timezone" => null,
            "utcname" => null,
            "utc" => null,
            "abbreviations" => null,
            "status" => 1,
        ];
    }

    protected function EditRequest($country)
    {
        $this->request = $country->only([
            "id",
            "iso",
            "name",
            "nicename",
            "iso3",
            "numcode",
            "phonecode",
            "region_id",
            "timezone",
            "utcname",
            "utc",
            "abbreviations",
            "status",
        ]);
    }

    public function updateStatus($id = null , $status = false):void
    {
        $country = Country::find($id);
        if ($country)
        {
            $country->status = $status?1:0;
            $country->save();
        }
    }

    public function destroy($id = null):void
    {
        $country = Country::find($id);
        if ($country)
        {
            $country->delete();
            $this->success('Country','Deleted successfully');
        }
    }
}
