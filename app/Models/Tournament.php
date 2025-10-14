<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{

    protected $fillable = [
        'type',
        'scheduled_event',
        'admission_price',
        'status',
        'places',
        'result'
    ];
}
