<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payement extends Model
{
    use HasFactory;

    // public function invoice()
    // {
    //     return $this->belongsTo(Invoice::class);
    // }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    protected $fillable = [
        'unit_id',
        'tenant_id',
        'building_id',
        'receipt',
        'amount',
        'payement_method',
        'reference',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reference' => 'string',
        'status' => 'string',
    ];
    protected $attributes = [
        'status' => 'pending',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
