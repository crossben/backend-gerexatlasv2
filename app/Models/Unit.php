<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function contract()
    {
        return $this->hasMany(Contract::class);
    }

    protected $fillable = [
        'building_id',
        'name',
        'surface',
        'type',
        'status',
        'reference',
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
    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }
    public function payements()
    {
        return $this->hasMany(Payement::class);
    }
}

