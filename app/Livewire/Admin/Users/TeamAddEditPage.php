<?php

namespace App\Livewire\Admin\Users;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

class TeamAddEditPage extends Component
{
    use Toast;
    public $code;

    public $final_id;

    public $request = [];
    public $countries = [];

    protected $validationAttributes = [
        'request.name'=>'name',
        'request.last_name'=>'last_name',
        'request.email'=>'email',
        'request.phone'=>'phone',
        'request.address'=>'address',
        'request.password'=>'password'
    ];

    protected function rules():array
    {
        return [
            'request.name'=>'required|max:255',
            'request.last_name'=>'max:255',
            'request.email'=>'required|email|unique:users,email,'.$this->final_id ??0,
            'request.phone'=>'max:255',
            'request.address'=>'max:5000',
            'request.password'=>'required|min:4|confirmed'
        ];
    }

    public function mount()
    {
        if (checkData($this->code))
        {
            $this->final_id = decryptId($this->code);
            $check = User::find($this->final_id);
            if ($check && $check->isAdmin())
            {
                $this->EditRequest($check);
            }
            else
            {
                return redirect()->route('admin::users.team')->with('error','Invalid team code');
            }
        }
        else
        {
            $this->NewRequest();
        }

        $this->countries = Country::where('status',1)->orderBy('name','asc')->get();
    }

    public function render()
    {
        return view('livewire.admin.users.team-add-edit-page');
    }

    public function Submit():void
    {
        $this->validate($this->rules());
        $this->create($this->request);
    }

    protected function create($data):void
    {
        try
        {
            $data['password'] = Hash::make($data['password']);
            $check = User::create(Arr::except($data,['password_confirmation']));
            $this->final_id = $check->id;
            $this->EditRequest($check);

            $this->dispatch('SweetMessage',
                type:'success',
                title:'New Team',
                message:'Created Successfully',
                url:route('admin::users.team')
            );
        }
        catch (\Exception $exception)
        {
            $this->error('New User',$exception->getMessage());
        }
    }

    public function Save():void
    {
        $rules = $this->rules();
        if (!checkData($this->request['password']))
        {
            $rules = Arr::except($rules,'request.password');
        }

        $this->validate($rules);
        $this->update($this->request);
    }

    protected function update($data):void
    {
        try
        {
            $check = User::find($this->final_id);
            if (checkData($data['password']))
            {
                $data['password'] = Hash::make($data['password']);
                $data = Arr::except($data,['password_confirmation']);
            }
            else
            {
                $data = Arr::except($data,['password','password_confirmation']);
            }
            $check->fill($data);
            $check->save();

            $this->dispatch('SweetMessage',
                type:'success',
                title:'Edit Team',
                message:'Updated Successfully',
                url:route('admin::users.team')
            );
        }
        catch (\Exception $exception)
        {
            $this->error('Edit User',$exception->getMessage());
        }
    }

    private function EditRequest($check): void
    {
        $this->request = $check->only([
            'user_type',
            'gender',
            'name',
            'last_name',
            'email',
            'phone',
            'image',
            'country',
            'state',
            'city',
            'zipcode',
            'address',
            'status',
        ]);

        $this->request['password'] = $this->request['password_confirmation'] = null;
    }

    private function NewRequest(): void
    {
        $this->request = [
            'user_type'=>'sub_admin',
            'gender'=>'Mr.',
            'name'=>null,
            'last_name'=>null,
            'email'=>null,
            'phone'=>null,
            'image'=>null,
            'password'=>null,
            'password_confirmation'=>null,
            'country'=>null,
            'state'=>null,
            'city'=>null,
            'zipcode'=>null,
            'address'=>null,
            'status'=>1,
        ];

    }

    public function searchCountry(string $value = ''): void
    {
        $this->countries = Country::where('status',1)
            ->where('name','LIKE',"%{$value}%")
            ->orderBy('name','asc')
            ->get();
    }
}
