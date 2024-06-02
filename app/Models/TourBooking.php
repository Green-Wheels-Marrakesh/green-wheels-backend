<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_type',
        'tour_mode',
        'guide',
    ];

    function booking() : BelongsTo {
        return $this->belongsTo(Booking::class);
    }
}
