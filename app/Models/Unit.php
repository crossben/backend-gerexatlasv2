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
    public function payements()
    {
        return $this->hasMany(Payement::class);
    }
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    protected $fillable = [
        'building_id',
        'manager_id',
        'name',
        'type',
        'tenant_name',
        'tenant_email',
        'tenant_phone',
        'start_date',
        'end_date',
        'rent_amount',
        'contract_type',
        'reference',
        'status',
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
}

