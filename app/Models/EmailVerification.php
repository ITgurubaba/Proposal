<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';

    protected $fillable = [
        'email',
        'code',
        'status',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'email','email');
    }
}
