<?php

namespace App\Helpers\Ecommerce;

use App\Models\Product;
use App\Rules\SlugValidate;
use Illuminate\Support\Arr;

trait WithProductConfiguration
{

    public static array $productTypeFilterOptions = [
        ['label'=>'All','value'=>'all'],
        ['label'=>'Size','value'=>'size'],
        ['label'=>'Color','value'=>'color'],

    ];

    public static function getValidationAttributes($prefix = ""):array
    {
        $data = [
            'name'=>'name',
            'slug'=>'slug',
            'description'=>'description',
            'sku'=>'sku',
            'stocks'=>'stocks',
            'status'=>'status',
        ];

        $data = collect($data)->mapWithKeys(function ($value, $key) use ($prefix) {
            return [$prefix . $key => $value];
        })->toArray();

        $metaData = [
            'metaRequest.title'=>'title',
            'metaRequest.description'=>'description',
        ];

        return Arr::collapse([$data,$metaData]);
    }

    public static function getValidationRules($prefix = "",$id = null):array
    {
        $data = [
            'name'=>'required|max:255',
            'slug'=>['required','max:255',new SlugValidate(['model_id'=>$id,'model_class'=>Product::class])],
            'stocks'=>'required|integer|min:0',
            'description'=>'max:500',
            'sku'=>'max:255',
        ];

        $data = collect($data)->mapWithKeys(function ($value, $key) use ($prefix) {
            return [$prefix . $key => $value];
        })->toArray();

        $metaData = [
            'metaRequest.title'=>'max:110',
            'metaRequest.description'=>'max:160',
        ];

        return Arr::collapse([$data,$metaData]);
    }

    public static function NewProductRequest():array
    {
        return [
            'category_id'=>null,
            'name'=>null,
            'sku'=>null,
            'stocks'=>50,
            'description'=>null,
            'content'=>null,

            'image'=>null,
            'hover_image'=>null,

            'market_price'=>0,
            'selling_price'=>0,
            'is_discount_applied'=>0,
            'has_pricing'=>0,

            'weight'=>null,
            'height'=>null,
            'width'=>null,
            'length'=>null,
            'is_video_uploaded'=>0,
            'video_type'=>'upload',
            'video_url'=>null,
            'video'=>null,
            'tags'=>null,
            'related_products'=>null,
            'is_featured'=>0,
            'status'=>1,
        ];
    }

    public static function getProductRequest(Product $product):array
    {
        return $product->only((new Product())->getFillable());
    }
}
