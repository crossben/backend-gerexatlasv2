<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Manager extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'reference',
        'address',
        'city',
        'country',
        'buildings_count',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reference' => 'string',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function tenant()
    {
        return $this->hasMany(Tenant::class);
    }
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
