<?php

namespace App\Helpers\Traits;

use App\Models\ProductPricing;
use App\Models\ProductType;

trait WithProductPricingAction
{
    public $productPricing = [];

    public $productColorAttributes = [];
    public $productSizeAttributes = [];

    public $productTypeListOptions = [];

    public function __construct()
    {
        $this->productTypeListOptions = ProductPricing::PRODUCT_TYPES;

        $this->productColorAttributes = ProductType::where('type',ProductType::TYPE_COLOR)
            ->orderBy('position','asc')
            ->get();
        $this->productSizeAttributes = ProductType::where('type',ProductType::TYPE_SIZE)
            ->orderBy('position','asc')
            ->get();
    }

    public function addNewProductPricing():void
    {
        $this->productPricing[] = [
            'product_id'=>$this->product_id ??null,
            'product_type'=>ProductType::TYPE_SIZE,
            'product_type_id'=>null,
            'sub_product_type_id'=>null,
            'market_price'=>0,
            'selling_price'=>0,
            'is_discount_applied'=>0,
            'position'=>0,
            'default'=>0,
        ];
    }

    public function removeProductPricing($index = null):void
    {
        if(\Arr::has($this->productPricing, $index))
        {
            $temp = $this->productPricing;
            \Arr::forget($temp, $index);
            $this->productPricing = array_values($temp);
        }
    }
}
