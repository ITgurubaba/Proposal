<?php

namespace App\Livewire\Admin\Auth;

use App\Rules\PasswordMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginPage extends Component
{
    public $request = [
        'email'=>null,
        'password'=>null,
    ];

    public $remember = false;


    protected $validationAttributes = [
        'request.email'=>'email',
        'request.password'=>'password',
    ];

    protected function rules():array
    {
        return [
            'request.email'=>'required|email|exists:users,email|max:255',
            'request.password'=>['required','min:3',new PasswordMatch($this->request['email'])]
        ];
    }

    public function render()
    {
        return view('livewire.admin.auth.login-page')
            ->layout('layouts.admin.auth');
    }

    public function login(): void
    {
        $this->validate($this->rules());
        $this->attemptLogin();
    }

    private function attemptLogin(): void
    {
        Auth::attempt($this->request,$this->remember);

        $this->redirect(route('admin::dashboard.main'));

    }
}
