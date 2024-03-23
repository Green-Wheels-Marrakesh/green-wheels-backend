<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bike extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bike_type',
        'bike_model',
        'bike_mark',
        'bike_status',
    ];

    function bike_variants() : HasMany {
        return $this->hasMany(BikeVariant::class);
    }
}
