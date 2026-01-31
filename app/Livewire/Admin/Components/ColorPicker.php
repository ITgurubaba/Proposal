<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class ColorPicker extends Component
{
    public $color = '#ffffff';
public $backgroundColor = '#ff0000'; // or any default color

    public function render()
    {
        return view('livewire.admin.components.color-picker');
    }
}
