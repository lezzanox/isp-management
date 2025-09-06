<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
        'disabled' => 'boolean',
        'activation' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function mikrotik()
    {
        return $this->belongsTo(Mikrotik::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function pppType()
    {
        return $this->belongsTo(PppType::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
