<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memberships extends Model
{
    protected $fillable = [
        'name',
        'description',
        'months_to_expire',
        'price'
    ];

    public function Subscriptions(){
        return $this->hasMany(Subscriptions::class, 'id_membership');
    }
}
