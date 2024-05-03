<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_end',
        'child',
        'guaranty_price',
    ];

    function operation() : BelongsTo {
        return $this->belongsTo(Operation::class);
    }
    function client() : BelongsTo {
        return $this->belongsTo(Client::class);
    }
    function bike_variant() : BelongsTo {
        return $this->belongsTo(BikeVariant::class);
    }
    function booking_additionals() : HasMany {
        return $this->hasMany(BookingAdditional::class);
    }
}
