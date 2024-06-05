<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'qty_notification_setting',
    ];
    protected $appends = [
        'in_stock',
    ];

    protected function inStock(): Attribute
    {
        return new Attribute(
            get: fn () => $this->bike_variants()->count(),
        );
    }
    function bike_variants() : HasMany {
        return $this->hasMany(BikeVariant::class);
    }
}
