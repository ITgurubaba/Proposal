<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPerson extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    /**
     * Get the client that owns the person.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
