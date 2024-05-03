<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingAdditional extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price',
    ];

    function product_variant() : BelongsTo {
        return $this->belongsTo(ProductVariant::class);
    }
    function booking() : BelongsTo {
        return $this->belongsTo(Booking::class);
    }
}
