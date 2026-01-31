<?php

namespace App\Helpers\Ecommerce;

use App\Models\Product;
use App\Models\ProductAdvertisement;
use App\Models\ShippingCharge;
use App\Models\VatTax;

class EcommerceHelper
{
    const PRODUCT_Quick_View = 'productQuickView';
    const PRODUCT_Add_To_Cart = 'addProductItemToCart';
    const PRODUCT_Add_To_Wishlist = 'addProductItemToWishlist';

    const MINI_CART_MODAL_OPEN = "openMiniCartModal";
    const MINI_CART_UPDATE = "miniCartUpdate";

    const CART_COUNT_UPDATE = "cartCountUpdate";

    public static function getBlogPostAds()
    {
        return ProductAdvertisement::where('name','Blog post ads')->where('status',1)->first();
    }

    public static function getRecentProducts($limit = 4)
    {
        return Product::where('status',1)
         ->orderBy('created_at', 'desc') // Sort by most recent
            ->limit($limit)
            ->get();
    }

    public static function getTypes($case = "tax"):array
    {
        $data = [];

        switch ($case) {
            default:
                foreach (VatTax::TYPES as $type) {
                    $data[] = [
                        'label'=>ucfirst($type),
                        'value'=>$type
                    ];
                }
        }

        return $data;
    }

}
