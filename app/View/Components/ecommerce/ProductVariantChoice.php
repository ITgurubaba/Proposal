<?php

namespace App\View\Components\ecommerce;

use App\Helpers\Ecommerce\ProductHelper;
use App\Models\Product;
use App\Models\ProductType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductVariantChoice extends Component
{
    public Product $product;

    public function __construct(
        $product,
    )
    {
        $this->product = $product;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $colorOptions = ProductHelper::getProductVariants($this->product, ProductType::TYPE_COLOR);
        $sizeOptions = ProductHelper::getProductVariants($this->product, ProductType::TYPE_SIZE);
     
        $productVariants = $this->product->pricing;
        return view('components.ecommerce.product-variant-choice', compact('colorOptions', 'sizeOptions','productVariants'));
    }
}
