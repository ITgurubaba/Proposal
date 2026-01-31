<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ProductCategory;

class SwiperCategoryItem extends Component
{
    public ProductCategory $category;

    /**
     * Create a new component instance.
     */
    public function __construct(ProductCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.swiper-category-item');
    }
}
