<?php

namespace App\View\Components\admin\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PhoneInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.forms.phone-input');
    }
}
