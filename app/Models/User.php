<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_type',
        'gender',
        'name',
        'last_name',
        'email',
        'phone',
        'image',
        'password',
        'country',
        'state',
        'city',
        'zipcode',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function shipping():HasMany
    {
        return $this->hasMany(UserShippingInformation::class,'user_id','id');
    }

    public function orders():HasMany
    {
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function getCountry():HasOne
    {
        return $this->hasOne(Country::class,'id','country');
    }

    public function isAdmin():bool
    {
        return  in_array($this->user_type,['admin','sub_admin']);
    }

    public function isUser():bool
    {
        return $this->user_type == 'user';
    }

    public function fullName():string
    {
        return $this->name." ".$this->last_name;
    }

    public function avatarUrl():string
    {
        return checkData($this->image)?asset($this->image):asset('assets/default/user.png');
    }

    public function userType(): string
    {
        return match ($this->user_type){
            "sub_admin"=>"Sub Admin",
            "admin"=>"Admin",
            default=>"User",
        };
    }

    public function isOnline():bool { return Cache::has('user-is-online-'.$this->id); }

    public function checkProductInWishlist($product_id = null):bool
    {
        return (bool)ProductWishlist::where([
            'product_id' => $product_id,
            'user_id' => $this->id
        ])->first()?->is_favorite;
    }

    public function displayAddress():?string
    {
        $address = "";

        if (checkData($this->city)){
            $address .= $this->city;
        }

        if (checkData($this->state)){
            $address .= ", ".$this->state;
        }

        if (checkData($this->country)){
            $address .= ", ".$this->getCountry?->nicename ??'';
        }

        return $address;
    }

}
