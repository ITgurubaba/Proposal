<?php

namespace App\Helpers\Ecommerce;

use App\Models\Product;
use App\Models\ProductWishlist;

trait WithProductWishlist
{

    public static function removeFromWishlist($productId = null, $userId = null):bool
    {
        ProductWishlist::where([
            'user_id' => $userId,
            'product_id'=>$productId
        ])->delete();

        return true;
    }

    public static function addToWishlist($product_id = null,$user_id = null):bool
    {
        $check = ProductWishlist::where([
            'user_id' => $product_id,
            'product_id'=>$user_id
        ])->first();

        if(!$check)
        {
            ProductWishlist::create([
                'user_id' =>$user_id,
                'product_id'=>$product_id,
                'is_favorite'=>1,
            ]);
        }

        return true;

    }
    public static function updateWishlist(Product $product,$user = null): void
    {
        $check = ProductWishlist::where([
            'user_id' => $user->id,
            'product_id'=>$product->id
        ])->first();

        if(!$check)
        {
            $check = new ProductWishlist();
            $check->fill([
                'user_id' => $user->id,
                'product_id'=>$product->id
            ]);
        }

        $check->is_favorite = ($check->is_favorite ?? 0) == 0 ? 1 : 0;
        $check->save();

    }

}
