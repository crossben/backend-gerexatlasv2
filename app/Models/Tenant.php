<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'manager_id',
        'name',
        'email',
        'phone',
        'nationality',
        'reference',
        'status',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reference' => 'string',
        'status' => 'string',
        'nationality' => 'string',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }
    public function payments()
    {
        return $this->hasMany(Payement::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function contracts()
    {
        return $this->hasOne(Contract::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

}
