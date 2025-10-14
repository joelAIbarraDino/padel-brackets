<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_tournament extends Model
{
    protected $fillable = [
        'name'
    ];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'type');
    }
}
