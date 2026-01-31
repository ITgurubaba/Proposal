<?php

namespace App\Helpers\Ecommerce;

use App\Models\Product;
use App\Models\ProductCategoryAttribute;
use App\Models\ProductImage;
use App\Models\ProductPricing;

class ProductHelper
{
    use WithProductConfiguration,
        WithProductVariant,
        WithProductWishlist;

    public static function getDefaultAttributeSelection(Product $product, $withDefault = false):array
    {


        if($withDefault && $product->has_pricing && $product->pricing()->exists())
        {
           $pricing =  $product->pricing->first();

           return [
               'quantity' => $product->stocks>0?1:0,
               'size'=>in_array($pricing->product_type,[ProductPricing::PRODUCT_TYPE_BOTH,ProductPricing::PRODUCT_TYPE_SIZE])
                   ?$pricing->product_type_id
                   :null,
               'color'=>in_array($pricing->product_type,[ProductPricing::PRODUCT_TYPE_BOTH,ProductPricing::PRODUCT_TYPE_COLOR])
                   ?($pricing->product_type ==ProductPricing::PRODUCT_TYPE_BOTH?$pricing->sub_product_type_id:$pricing->product_type_id)
                   :null,
           ];

        }

        return [
            'quantity'=>1,
            'color'=>null,
            'size'=>null,
        ];
    }

    public static function getProductCategoriesId(Product $product):array
    {
        return ProductCategoryAttribute::where([
            'product_id'=>$product->id
        ])->orderBy('position','asc')->pluck('category_id')->toArray();
    }

    public static function getProductImagesRequest(Product $product):array
    {
        return ProductImage::select([
            'id',
            'product_id',
            'name',
            'path',
            'position',
        ])
        ->where('product_id',$product->id)
            ->orderBy('position','asc')
            ->get()
            ->toArray();
    }

    public static function getProductPricingRequest(Product $product):array
    {
        return ProductPricing::select([
            'id',
            'product_id',
            'product_type',
            'product_type_id',
            'sub_product_type_id',
            'market_price',
            'selling_price',
            'stocks',
            'is_discount_applied',
            'position',
            'default',
        ])
            ->where('product_id',$product->id)
            ->orderBy('position','asc')
            ->get()
            ->toArray();
    }

    public static function createOrUpdateCategories(Product $product, $data = []):bool
    {
        try
        {
            $ids = [];

            foreach ($data as $i=>$item)
            {
               $check =  ProductCategoryAttribute::where([
                    'product_id'=>$product->id,
                    'category_id'=>$item,
                ])->first();

               if(!$check)
               {
                   $check = new ProductCategoryAttribute();
                   $check->fill([
                       'product_id'=>$product->id,
                       'category_id'=>$item,
                   ]);
               }

               $check->position = $i + 1;
               $check->save();

               $ids[] = $check->id;

            }

            ProductCategoryAttribute::where('product_id',$product->id)
                ->whereNotIn('id',$ids)
                ->delete();

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public static function createOrUpdateImages(Product $product, $data = []):array
    {
        try
        {
            $ids = [];

            foreach ($data as $i=>$item)
            {

                $check = null;

                $data['product_id'] = $item['product_id'];

                if(\Arr::has($item,'id'))
                {
                    $check = ProductImage::find($item['id']);
                }

                if(!$check)
                {
                    $check = new ProductImage();
                    $check->fill([
                        'product_id'=>$product->id,
                    ]);
                }

                $check->fill([
                    'name'=>$item['name'],
                    'path'=>$item['path'],
                    'position'=>$i+1,
                ]);

                $check->save();

                $ids[] = $data[$i]['id'] = $check->id;

            }

            ProductImage::where('product_id',$product->id)
                ->whereNotIn('id',$ids)
                ->delete();

            return $data;
        }
        catch (\Exception $e) {
            return $data;
        }
    }

    public static function createOrUpdatePricing(Product $product, $data = []):array
    {
        try
        {
            $ids = [];

            foreach ($data as $i=>$item)
            {

                $check = null;

                $data['product_id'] = $item['product_id'];

                if(\Arr::has($item,'id'))
                {
                    $check = ProductPricing::find($item['id']);
                }

                if(!$check)
                {
                    $check = new ProductPricing();
                }

                $check->fill([
                    'product_id'=>$product->id,
                    'product_type'=>$item['product_type'],
                    'product_type_id'=>$item['product_type_id'],
                    'sub_product_type_id'=>$item['sub_product_type_id'],
                    'market_price'=>$item['market_price'],
                    'selling_price'=>$item['selling_price'],
                     'stocks'=>$item['stocks'],
                    'is_discount_applied'=>$item['is_discount_applied'],
                    'position'=>$item['position'],
                    'default'=>$item['default'],
                ]);

                $check->save();

                $ids[] = $data[$i]['id'] = $check->id;

            }

            ProductPricing::where('product_id',$product->id)
                ->whereNotIn('id',$ids)
                ->delete();

            return $data;
        }
        catch (\Exception $e) {
            return $data;
        }
    }

}
