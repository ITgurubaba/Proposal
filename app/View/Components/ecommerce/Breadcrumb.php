<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helpers\Ecommerce\PageHelper;
use App\Models\ThemeSetting;

class Breadcrumb extends Component
{
    public string $bgImage;

    public string $title = "";
    public string $menuItem = "";
    
    public string $key = "pages_uper_banner";
    
    

    /**
     * Create a new component instance.
     */
    public function __construct(
        
        $title = "",
        $menuItem = "",
   
        $bgImage = "assets/frontend/images/breadcrumb/bg/b3.png"
       
    )
    {
       
        $this->title = $title;
        $this->menuItem = $menuItem;
    
        $uper_banner_img = ThemeSetting::findByKey($this->key)->value;

        // If value exists, override default background image
        $this->bgImage = $uper_banner_img ?: $bgImage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.breadcrumb');
    }
}
