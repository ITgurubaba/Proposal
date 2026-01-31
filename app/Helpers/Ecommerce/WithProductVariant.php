<?php

namespace App\Helpers\Ecommerce;

use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\ProductType;

trait WithProductVariant
{

    public static function getProductVariants(Product $product, $type = "color")
    {
        $ids = $product->pricing()
            ->where('product_type', $type)
            ->pluck('product_type_id')
            ->toArray();

        $otherIds = $type == ProductType::TYPE_COLOR
            ?$product->pricing()
                ->where('product_type', ProductPricing::PRODUCT_TYPE_BOTH)
                ->pluck('sub_product_type_id')
                ->toArray()
            :$product->pricing()
                ->where('product_type', ProductPricing::PRODUCT_TYPE_BOTH)
                ->pluck('product_type_id')
                ->toArray();

        $variantIds = collect($ids)->merge($otherIds);

        return ProductType::whereIn('id', $variantIds)->get();

    }

    public static function getProductVariantByType(Product $product, $id = null, $type = "color")
    {
        $column = $type == ProductType::TYPE_COLOR?'sub_product_type_id':'product_type_id';
        $columnNeed = $type == ProductType::TYPE_COLOR?'product_type_id':'sub_product_type_id';

        $ids = $product->pricing()
            ->where('product_type', ProductPricing::PRODUCT_TYPE_BOTH)
            ->where($column, $id)
            ->pluck($columnNeed)
            ->toArray();

        return ProductType::where('type',$type == ProductType::TYPE_COLOR ? ProductType::TYPE_SIZE : ProductType::TYPE_COLOR)
            ->whereIn('id', $ids)
            ->where('status',1)
            ->get();
    }

    public static function getProductVariantPrice(
       Product $product,
       $attributes = [],
    ): int|float
    {
        if(!$product->has_pricing)
        {
            return (float) $product->getDefaultPrice(withCurrency: false);
        }

        if(checkData($attributes['color']) && checkData($attributes['size']))
        {
            $pricing = ProductPricing::where([
                'product_id'=>$product->id,
                'product_type'=>ProductPricing::PRODUCT_TYPE_BOTH,
            ])->where('product_type_id', $attributes['size'])
                ->where('sub_product_type_id', $attributes['color'])
                ->first();

            if($pricing)
            {
                return (float) ($pricing->selling_price ??0);
            }
        }

        if(checkData($attributes['color']))
        {
            $pricing = ProductPricing::where([
                'product_id'=>$product->id,
                'product_type'=>ProductPricing::PRODUCT_TYPE_COLOR,
            ])->where('product_type_id', $attributes['color'])
                ->first();

            if($pricing)
            {
                return (float) ($pricing->selling_price ??0);
            }
        }

        if(checkData($attributes['size']))
        {
            $pricing = ProductPricing::where([
                'product_id'=>$product->id,
                'product_type'=>ProductPricing::PRODUCT_TYPE_SIZE,
            ])->where('product_type_id', $attributes['size'])
                ->first();

            if($pricing)
            {
                return (float) ($pricing->selling_price ??0);
            }
        }

        return 0;
    }

}
