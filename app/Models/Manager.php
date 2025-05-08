<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'building_id',
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
}
