<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payement extends Model
{
    use HasFactory;

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    protected $fillable = [
        'unit_id',
        'manager_id',
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
