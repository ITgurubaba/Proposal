<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

class NotificationDrawer extends Component
{
    use Toast;
    public $notificationDrawer = false;

    public function mount()
    {
        if (session()->has('error'))
        {
            $this->error('Error!',session()->get('error'));
        }

        if (session()->has('success'))
        {
            $this->error(config('settings.site_name'),session()->get('error'));
        }
    }

    #[On('SetMessage')]
    public function displayToastMessage($type = "error",$message = ""):void
    {
        if($type == "success"){
            $this->success(config('settings.site_name'),$message);
        }
        elseif($type == "info"){
            $this->info(config('settings.site_name'),$message);
        }
        else{
            $this->error(config('settings.site_name'),$message);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.notification-drawer');
    }

}
