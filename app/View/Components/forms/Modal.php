<?php

namespace App\View\Components\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public string $title;

    public mixed $body;

    public mixed $footer;

    public ?string $description;

    public string $size;


    /**
     * Create a new component instance.
     */
    public function __construct($title = "",$body = "",$footer = null,$description = null,$size = "")
    {
        $this->body = $body;
        $this->footer = $footer;
        $this->title = $title;
        $this->description = $description;
        $this->size = $size;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.modal');
    }
}
