<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'amount',
        'id_transaction',
        'currency',
        'method',
        'status'
    ];
}
