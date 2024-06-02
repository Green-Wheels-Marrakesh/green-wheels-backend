<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingCharge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'charge_date',
        'charge_price',
        'charge_description',
    ];

    function booking() : BelongsTo {
        return $this->belongsTo(Booking::class);
    }
}
