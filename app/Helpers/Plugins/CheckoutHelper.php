<?php

namespace App\Helpers\Plugins;

use App\Models\Order;
use App\Models\OrderGuest;
use App\Models\OrderItem;
use App\Models\OrderGuestItem;
use App\Models\OrderGuestPayment;
use App\Models\OrderPayment;
use App\Models\OrderGuestShippingInformation;
use App\Models\OrderShippingInformation;
use App\Models\ProductType;
use App\Models\User;
use App\Models\UserShippingInformation;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CheckoutHelper
{

    public ?User $user;
    public bool $isAuthenticated = false;
    public ?CartHelper $cartHelper;

    public function __construct()
    {
        $this->isAuthenticated = Auth::check();

        if ($this->isAuthenticated)
        {
            $this->user = Auth::user();
        }

        $this->cartHelper = new CartHelper();
    }

    public function checkout(
        $shippingAddress = [],
        $isNewShippingAddress = false,
        $paymentMode = "cod"
    )
    {
        try {
            // ✅ Guest validation
            if (!$this->isAuthenticated) {
                if (
                    empty($shippingAddress['first_name']) ||
                    empty($shippingAddress['last_name']) ||
                    empty($shippingAddress['email'])
                ) {
                    return [
                        'success' => false,
                        'message' => 'Guest users must provide first name, last name, and email.',
                    ];
                }
            }
    
            $userShippingInformation = $this->createOrUpdateShippingInformation(
                $shippingAddress,
                $isNewShippingAddress,
            );
    
            $couponCondition = $this->cartHelper->getCondition(false, CartHelper::COUPON_CONDITION);
            $taxCondition = $this->cartHelper->getCondition(false, CartHelper::TAX_CONDITION);
            $shippingCondition = $this->cartHelper->getCondition(false, CartHelper::SHIPPING_CONDITION);
    
            // $order = new Order();
            $isGuest = !$this->isAuthenticated;
            $order = $isGuest ? new OrderGuest() : new Order();


            $baseOrderData = [
                'first_name' => $shippingAddress['first_name'] ?? $this->user->name ?? '',
                'last_name' => $shippingAddress['last_name'] ?? $this->user->last_name ?? '',
                'email' => $shippingAddress['email'] ?? $this->user->email ?? '',
            ];
            
            if (!$isGuest) {
                $baseOrderData['user_id'] = $this->user->id;
            }
            
            $order->fill($baseOrderData);
    
            $subTotal = $this->cartHelper->getTotal();
    
            if ($couponCondition) {
                $couponDiscountAmount = $couponCondition->getCalculatedValue($subTotal);
    
                $order->fill([
                    'is_discount_applied' => true,
                    'coupon_label' => $couponCondition->getAttributes()['model']->name ?? '',
                    'coupon_description' => $couponCondition->getAttributes()['model']->description ?? '',
                    'coupon_code' => $couponCondition->getAttributes()['model']->code ?? '',
                    'coupon_type' => $couponCondition->getAttributes()['model']->type ?? '',
                    'coupon_rate' => $couponCondition->getAttributes()['model']->amount ?? '',
                    'discount_amount' => $couponDiscountAmount,
                ]);
    
                $subTotal = max($subTotal - $couponDiscountAmount, 0);
            }
    
            if ($taxCondition) {
                $order->fill([
                    'is_tax_applied' => 1,
                    'tax_label' => $taxCondition->getAttributes()['model']->name ?? '',
                    'tax_type' => $taxCondition->getAttributes()['model']->type ?? '',
                    'tax_rate' => $taxCondition->getAttributes()['model']->rate ?? '',
                    'tax_amount' => $taxCondition->getCalculatedValue($subTotal),
                ]);
            }
    
            $order->fill([
                'sub_total' => $this->cartHelper->getSubTotal(),
                'total' => $this->cartHelper->getTotal(),
                'order_date' => now()->toDateTimeString(),
                'status' => Order::STATUS_CREATED,
            ]);
    
            $order->save();
    
            $this->processOrder(
                order: $order,
                shippingInformation: $userShippingInformation,
            );
    
            switch ($paymentMode) {
                case "stripe":
                case "paypal":
                default:
                if ($this->isAuthenticated) {
                    OrderPayment::create([
                        'order_id' => $order->id,
                        'payment_method' => 'cod',
                        'payment_id' => null,
                        'transaction_id' => null,
                        'response' => null,
                        'errors' => null,
                        'paid' => 0,
                    ]);
                }
                else{
                    OrderGuestPayment::create([
                        'order_id' => $order->id,
                        'payment_method' => 'cod',
                        'payment_id' => null,
                        'transaction_id' => null,
                        'response' => null,
                        'errors' => null,
                        'paid' => 0,
                    ]);
                }
    
                    $order->status = Order::STATUS_PENDING;
                    $order->save();
    
                    $this->cartHelper->clearCart();
    
                    return [
                        'success' => true,
                        'url' => route('frontend::orders:checkout-success', ['code' => encryptId($order->id)]),
                        'message' => __('Payment was successful'),
                    ];
            }
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ];
        }
    }
    

    public function processOrder(
        $order,
        UserShippingInformation $shippingInformation,
    )
    
    {
        // Saving Order Items
        $data = $this->cartHelper->getCartData();
        foreach ($data as $item)
        {
            $p_attrs = explode('_',$item->id);

            $product_id = $p_attrs[0];
            $color_id = $p_attrs[1];
            $size_id  = $p_attrs[2];

            $color = ProductType::where('type',ProductType::TYPE_COLOR)
                ->where('id',$color_id)
                ->first();
            $size = ProductType::where('type',ProductType::TYPE_SIZE)
                ->where('id',$size_id)
                ->first();
            if ($this->isAuthenticated) {
            OrderItem::create([
                'order_id'=>$order->id,
                'product_id'=>$item->associatedModel->id,
                'size'=>$size?->name ??null,
                'color'=>$color?->name ??null,
                'color_code'=>$color?->color_code ??null,
                'name'=>$item->associatedModel->name,
                'image'=>$item->associatedModel->image,
                'price'=>$item->price,
                'quantity'=>$item->quantity,
                'total'=>$item->getPriceSum(),
            ]);
        }
        else{
            OrderGuestItem::create([
                'order_id'=>$order->id,
                'product_id'=>$item->associatedModel->id,
                'size'=>$size?->name ??null,
                'color'=>$color?->name ??null,
                'color_code'=>$color?->color_code ??null,
                'name'=>$item->associatedModel->name,
                'image'=>$item->associatedModel->image,
                'price'=>$item->price,
                'quantity'=>$item->quantity,
                'total'=>$item->getPriceSum(),
            ]);
        }
        }

        // Order Shipping Information
        if ($this->isAuthenticated) {
        OrderShippingInformation::create(Arr::collapse([
            ['order_id'=>$order->id],
            $shippingInformation->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'phone_2',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country',
            ])
        ]));
        }
        else{
            OrderGuestShippingInformation::create(Arr::collapse([
                ['order_id'=>$order->id],
                $shippingInformation->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'phone_2',
                    'address_line_1',
                    'address_line_2',
                    'city',
                    'state',
                    'postal_code',
                    'country',
                ])
            ]));

        }

    }

    public function createOrUpdateShippingInformation(
        $shippingAddress = [],
        $isNewShippingAddress = false,
    )
    {
        if ($isNewShippingAddress)
        {
            $userShippingAddress = new UserShippingInformation();
            $shippingAddress['is_default'] = 1;
        }
        else
        {
            $userShippingAddress = UserShippingInformation::find($shippingAddress['id']);
        }

        // $shippingAddress['user_id'] = $this->user->id;
        $shippingAddress['user_id'] = $this->user->id ?? null;

        $userShippingAddress->fill(Arr::except($shippingAddress, 'id'));

        return $userShippingAddress;
    }

    public static function newShippingRequest($user = null):array
    {
        if ($user && $user->shipping()->exists()){

            return $user->shipping->first()->only(
                Arr::collapse([
                    ['id'],
                    (new UserShippingInformation())->getFillable()
                ])
            );
        }

        if ($user){
            return [
                'user_id'=>$user?->id ??null,
                'first_name'=>$user->name,
                'last_name'=>$user->last_name,
                'email'=>$user->email,
                'phone'=>$user->phone,
                'phone_2'=>null,
                'address_line_1'=>null,
                'address_line_2'=>null,
                'city'=>null,
                'state'=>null,
                'postal_code'=>null,
                'country'=>"Nepal",
            ];
        }

        return [
            'user_id'=>$user?->id ??null,
            'first_name'=>null,
            'last_name'=>null,
            'email'=>null,
            'phone'=>null,
            'phone_2'=>null,
            'address_line_1'=>null,
            'address_line_2'=>null,
            'city'=>null,
            'state'=>null,
            'postal_code'=>null,
            'country'=>"Nepal",
        ];
    }

    public function authenticateWithCart(User $user): array
    {
        try {
            // Retrieve the session cart items
            $sessionCartItems = $this->cartHelper->getCartData();

            // Optionally, you can retrieve conditions if needed
            $conditions = $this->cartHelper->getCondition(all: true);

            // Get the user's cart session
            $userCart = \Cart::session($user->id);

            // Check if the user's cart is empty
            if ($userCart->getContent()->isNotEmpty()) {
                $userCart->clear();
            }

            // Loop through each item in the session cart and add it to the user's cart
            foreach ($sessionCartItems as $item)
            {
                $userCart->add(array(
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'attributes' => $item->attributes,
                    'associatedModel' => $item->associatedModel,
                    'conditions' => $conditions,
                ));
            }


            // Optionally clear the session cart after copying
            $this->cartHelper->clearCart();

            return [
                'success' => true,
                'message' => 'Login Successful. Cart copied to user successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function processOrderForCheckout($paymentMethod = OrderHelper::PAYMENT_COD): void
    {

    }

}
