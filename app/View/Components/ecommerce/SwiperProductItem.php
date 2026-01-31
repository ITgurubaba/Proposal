<?php

namespace App\View\Components\ecommerce;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SwiperProductItem extends Component
{
    public Product $product;
    public mixed $productRating;

    /**
     * Create a new component instance.
     */
    public function __construct($product)
    {
        $this->product = $product;
        $this->productRating = $product->getRating();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.swiper-product-item');
    }
}
