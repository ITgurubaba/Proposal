<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Larinfo;

class ServerInfoPage extends Component
{
    public $data;

    public function mount()
    {
        $this->data = Larinfo::getInfo();
    }

    public function render()
    {
        return view('livewire.admin.settings.server-info-page');
    }
}
