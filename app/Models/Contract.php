<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }

    protected $fillable = [
        'tenant_id',
        'unit_id',
        'contract_type',
        'contract_body',
        'start_date',
        'end_date',
        'rent_amount',
        'reference',
        'status',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reference' => 'string',
        'status' => 'string',
    ];
    protected $dates = [
        'start_date',
        'end_date',
    ];
}
