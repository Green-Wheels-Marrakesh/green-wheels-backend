<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class bookingDamage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'damage_type',
        'damage_date',
        'damage_estimated_price',
        'damage_description',
    ];

    function booking_detail() : BelongsTo {
        return $this->belongsTo(BookingDetail::class);
    }
}
