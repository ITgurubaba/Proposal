<?php

namespace App\Helpers\Plugins;

use App\Helpers\Ecommerce\ProductHelper;
use App\Models\DiscountCoupon;
use App\Models\Product;
use App\Models\User;
use App\Models\VatTax;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\CartCondition;

class CartHelper
{
    const COUPON_CONDITION = "COUPON";
    const TAX_CONDITION = "TAX";

    const SHIPPING_CONDITION = "SHIPPING";

    public bool $isAuthenticated = false;

    public ?User  $user;

    public mixed $cart;

    public mixed $sessionId = 0;

    public function __construct()
    {
        if (auth()->check())
        {
            $this->isAuthenticated = true;
            $this->user = auth()->user();
            $this->sessionId = $this->user->id;
        }
        else
        {
            $this->sessionId = session()->getId();
        }

        $this->cart = \Cart::session($this->sessionId);
    }

    public function getCartData()
    {
        return $this->cart->getContent();
    }

    public function getCondition($all = false, $condition = self::COUPON_CONDITION)
    {
        if ($all)
        {
            return $this->cart->getConditions();
        }

        return $this->cart->getCondition($condition);
    }

    public function getTotal()
    {
        return $this->cart->getTotal();
    }

    public function getSubTotal($condition = false)
    {
        if($condition)
        {
            return $this->cart->getSubTotal();
        }

        return $this->cart->getSubTotalWithoutConditions();
    }

    public function countItems()
    {
        return $this->cart->getContent()->count();
    }

    public function getRowId($product, $attributes):string
    {
        return $product->id.'_'.($attributes['color'] ??0).'_'.($attributes['size'] ??0);
    }

    public function addToCart(
        Product $product,
        $quantity = 1,
        $attributes = []
    ) {

        $price = ProductHelper::getProductVariantPrice(
            $product,
            $attributes
        );

        $rowId = $this->getRowId($product, $attributes);

        $check = $this->cart->get($rowId);

        if($check)
        {
            $this->cart->update($rowId,[
                'quantity' => $quantity,
            ]);

            return [
                'success' => true,
                'message' => __("Product added to cart successfully.")
            ];
        }

        // Add the item to the cart
        $this->cart->add([
            'id' => $rowId,
            'name' => $product->name,
            'quantity' => $quantity,
            'price' => $price,
            'attributes' => $attributes,
            'associatedModel' => $product
        ]);

        return [
            'success' => true,
            'message' => __("Product added to cart successfully.")
        ];
    }

    public function updateCartQuantity($rowId,$quantity = 1)
    {
        $check = $this->cart->get($rowId);
        if($check)
        {
            $this->cart->update($rowId,[
                'quantity' => $quantity,
            ]);

            return [
                'success' => true,
                'message' => __("Product added to cart successfully.")
            ];
        }
    }

    public function applyCoupon(DiscountCoupon $coupon): array
    {
        try
        {
            $condition = new CartCondition(array(
                'name' => self::COUPON_CONDITION,
                'type' => 'coupon',
                'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => "-".$coupon->discountLabel(false),
                'order' => 1,
                'attributes' => [
                    'model'=>$coupon,
                ],
            ));

            $this->cart->condition($condition);

            return [
                'success' => true,
                'message' => __("Coupon code applied successfully.")
            ];
        }
        catch (\Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function removeCoupon(): array
    {
        try
        {
            $this->removeCartCondition(self::COUPON_CONDITION);

            return [
                'success' => true,
                'message' => __("Coupon code removed.")
            ];
        }
        catch (\Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function removeFromCart($rowId = null)
    {
        $this->cart->remove($rowId);

        return [
            'success' => true,
            'message' => __("Product removed from cart successfully.")
        ];

    }


    public function removeCartCondition($conditionName = self::COUPON_CONDITION):void
    {
        $this->cart->removeCartCondition($conditionName);
    }

    public function clearCartConditions():array
    {
        $this->cart->clearCartConditions();

        return [
            'success' => true,
            'message' => __("Cart conditions cleared successfully.")
        ];
    }

    public function clearCart($withConditions = true):void
    {
        if ($withConditions){
            $this->cart->clearCartConditions();
        }
        $this->cart->clear();
    }

    public function initializeConditions():array
    {
        try
        {
            $tax = VatTax::where('status',1)->first();

            if ($tax)
            {
                $condition = new CartCondition(array(
                    'name' => self::TAX_CONDITION,
                    'type' => 'tax',
                    'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                    'value' => $tax->discountLabel(false),
                    'order' => 2,
                    'attributes' => [
                        'model'=>$tax,
                    ],
                ));

                $this->cart->condition($condition);
            }
        }
        catch (\Exception $e){}

        return [
            'success' => true,
            'message' => __("Cart conditions updated successfully.")
        ];
    }

}
