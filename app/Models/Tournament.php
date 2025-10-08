<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'type',
        'scheduled_event',
        'admission_price',
        'status',
        'result'
    ];
}
