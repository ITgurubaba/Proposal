<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserNavigation extends Component
{
    public ?string $currentTab;

    /**
     * Create a new component instance.
     */
    public function __construct($currentTab = "dashboard")
    {
        $this->currentTab = $currentTab;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.user-navigation');
    }
}
