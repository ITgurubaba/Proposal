<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Country;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

class AdminProfilePage extends Component
{
    use Toast;

    public $user;

    public $request = [];

    public $passwordRequest = [];

    public $selectedTab = "profile";

    public $countries = [];

    public $photoModal = false;

    protected $validationAttributes = [
        'request.name'=>'name',
        'request.last_name'=>'last_name',
        'request.email'=>'email',
        'request.phone'=>'phone',
        'request.address'=>'address',

        'passwordRequest.old_password'=>'old password',
        'passwordRequest.password'=>'password'
    ];

    protected function rules():array
    {
        return [
            'request.name'=>'required|max:255',
            'request.last_name'=>'max:255',
            'request.email'=>'required|email|unique:users,email,'.$this->user?->id ??0,
            'request.phone'=>'max:255',
            'request.address'=>'max:5000',
        ];
    }

    public function mount():void
    {
        $this->countries = Country::where('status',1)->orderBy('name','asc')->get();
        $this->user = User::find(auth()->user()->id);

        $this->EditRequest($this->user);
        $this->NewPasswordRequest();
    }

    public function render()
    {
        return view('livewire.admin.settings.admin-profile-page');
    }

    public function saveProfile():void
    {
        $this->validate($this->rules());

        $this->user->fill($this->request);

        $this->user->save();

        $this->success(title:'Profile',description: 'Saved successfully');

    }

    public function saveAvatar(): void
    {
        $this->user->image = $this->request['image'];
        $this->user->save();

        $this->photoModal = false;

        $this->success(title:'Avatar',description: 'Saved successfully');
    }

    public function changePassword():void
    {
        $this->validate([
            'passwordRequest.old_password'=>['required',new MatchOldPassword()],
            'passwordRequest.password'=>'required|min:4|confirmed'
        ]);
        $password = Hash::make($this->passwordRequest['password']);
        $this->user->password = $password;
        $this->user->save();

        $this->NewPasswordRequest();

        $this->success(title:'Change Password',description:'Password changed successfully');

    }

    private function EditRequest($check):void
    {
        $this->request = $check->only([
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
        ]);
    }

    public function searchCountry(string $value = ''): void
    {
        $this->countries = Country::where('status',1)
            ->where('name','LIKE',"%{$value}%")
            ->orderBy('name','asc')
            ->get();
    }

    private function NewPasswordRequest():void
    {
        $this->passwordRequest = [
            'old_password'=>null,
            'password'=>null,
            'password_confirmation'=>null,
        ];
    }
}
