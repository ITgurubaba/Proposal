<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_type',
        'company_registration_number',
        'contact_name',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'zip_code',
        'country',
    ];

    /**
     * Get the persons for the client.
     */
    public function persons(): HasMany
    {
        return $this->hasMany(ClientPerson::class);
    }

    public function proposals()
{
    return $this->hasMany(Proposal::class);
}

}
