<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    protected $fillable = [
        'manager_id',
        'name',
        'type',
        'number_of_units',
        'city',
        'address',
        'description',
        'reference',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'number_of_units' => 'integer',
        'type' => 'string',
        'status' => 'string',
        'duration' => 'integer',
        'duration_unit' => 'string',
        'reference' => 'string',
    ];
}
