<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public function places()
    {
        return $this->hasMany(Places::class, 'id_user');
    }

    public function tournaments()
    {
        return $this->hasManyThrough(
            Tournament::class, // Modelo final
            Places::class,      // Modelo intermedio
            'id_user',         // Foreign key en places
            'id',              // Foreign key en tournaments
            'id',              // Local key en users
            'id_tournament'    // Local key en places
        );
    }

    // Suscripciones (membresías compradas)
    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'id_user');
    }

    // Membresía activa del usuario
    public function activeSubscription()
    {
        return $this->hasOne(Subscriptions::class, 'id_user')
                    ->where('expired_at', '>', now());
    }
}
