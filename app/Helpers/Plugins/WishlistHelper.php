<?php

namespace App\Helpers\Plugins;

use App\Helpers\Ecommerce\ProductHelper;
use App\Models\Product;
use App\Models\ProductWishlist;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class WishlistHelper
{
    public bool $authenticated = false;

    public ?User $user;
    public array $sessionData = [];

    public function __construct()
    {
        $this->authenticated = auth()->check();
        if($this->authenticated)
        {
            $this->user = auth()->user();
            $this->sessionData = ProductWishlist::where('user_id', $this->user->id)->orderBy('id','desc')->pluck('product_id')->toArray();
        }
        else
        {
            $this->sessionData = Session::has('wishlist') ? Session::get('wishlist') : [];
        }
    }

    public function addToWishList($productId = null):array
    {
        if($this->authenticated)
        {
            ProductHelper::addToWishlist($productId,$this->user->id);
        }

        if (!in_array($productId, $this->sessionData))
        {
            $this->sessionData[] = $productId;
            $this->storeSession();
            return  [
                'success'=>true,
                'message'=>'Added to wishlist'
            ];
        }
        else
        {
            return  [
                'success'=>false,
                'message'=>'Already in wishlist'
            ];
        }
    }

    public function removeFromWishList($productId = null):array
    {
        if($this->authenticated)
        {
            ProductHelper::removeFromWishlist($productId,$this->user->id);
        }

        $key = array_search($productId, $this->sessionData);
        if($key !== false)
        {
            Arr::forget($this->sessionData, $key);
        }

        $this->storeSession();

        return [
            'success'=>true,
            'message'=>'Removed from wishlist'
        ];
    }

    public function getProducts($withPagination = false, $perPage = 10)
    {
        $order = count($this->sessionData)>0?implode(',',array_reverse($this->sessionData)):'0';

        $data = Product::query();

        $data->where('status',1);

        $data->whereIn('id', $this->sessionData);

        $data->orderByRaw("FIELD(id,$order)");

        if(!$withPagination)
        {
            return $data->get();
        }

        return $data->paginate($perPage);

    }

    private function storeSession():void
    {
        if(!$this->authenticated)
        {
            Session::put('wishlist', $this->sessionData);
        }
    }


}
