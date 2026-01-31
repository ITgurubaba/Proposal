<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMail extends Model
{
    use HasFactory;
   
    const STATUS_SEEN = 1;
    const STATUS_UNSEEN = 0;

    const STATUS = [
        self::STATUS_SEEN => 'Seen',
        self::STATUS_UNSEEN => 'Unseen',
    ];
   
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at'=>'datetime'
    ];

    public function fullName():string
    {
        return $this->first_name." ".$this->last_name;
    }

}
