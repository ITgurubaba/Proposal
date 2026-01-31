<?php

namespace App\View\Components\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class inputLabel extends Component
{
    /**
     * Create a new component instance.
     */

    public ?string $label;
    public ?string $description;

    public function __construct(
        $label = null,
        $description = null,
    )
    {
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input-label');
    }
}
