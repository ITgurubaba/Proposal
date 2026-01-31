<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NiceSelect extends Component
{
    public mixed $options = [];

    public string $optionLabel;
    public string $optionValue;

    public ?string $placeHolder;

    public function __construct(
        $options = [],
        $optionLabel = "name",
        $optionValue = "id",
        $placeHolder = null
    )
    {
        $this->options = $options;
        $this->optionLabel = $optionLabel;
        $this->optionValue = $optionValue;
        $this->placeHolder = $placeHolder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.nice-select');
    }
}
