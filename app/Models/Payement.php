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
        'receipt',
        'amount',
        'date',
        'method',
        'reference',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reference' => 'string',
        'status' => 'string',
    ];
    protected $attributes = [
        'status' => 'pending',
    ];
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];
}
