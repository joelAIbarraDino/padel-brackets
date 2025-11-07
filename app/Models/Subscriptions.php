<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $fillable = [
        'id_user',
        'id_membership',
        'id_payment',
        'expired_at'
    ];

    public function membership(){
        return $this->belongsTo(Memberships::class, 'id_membership');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function payment(){
        return $this->belongsTo(Payments::class, 'id_payment');
    }
}
