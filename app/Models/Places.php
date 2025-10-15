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

    public function tournament(){
        return $this->belongsTo(Tournament::class, 'id_tournament');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
