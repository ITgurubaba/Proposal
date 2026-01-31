<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShippingInformation extends Model
{
    protected $table = "user_shipping_information";

    protected $fillable = [
        'user_id',
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
        'is_default',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fullName():string
    {
        return $this->first_name.' '.$this->last_name;
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
            $address .= ", ".$this->country;
        }

        return $address;
    }
}
