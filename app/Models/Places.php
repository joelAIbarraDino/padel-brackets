<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    protected $fillable = [
        'id_user',
        'id_tournament',
        'id_payment',
        'status'
    ];
}
