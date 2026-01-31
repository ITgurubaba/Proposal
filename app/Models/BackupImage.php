<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'path',
        'status',
    ];
}
