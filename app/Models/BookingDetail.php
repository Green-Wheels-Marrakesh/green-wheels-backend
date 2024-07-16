<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_price',
        'guaranty_price',
        'for_child',
    ];

    function bike_variant() : BelongsTo {
        return $this->belongsTo(BikeVariant::class);
    }
    function booking() : BelongsTo {
        return $this->belongsTo(Booking::class);
    }
    function booking_damages() : HasMany {
        return $this->hasMany(BookingDamage::class);
    }
}
