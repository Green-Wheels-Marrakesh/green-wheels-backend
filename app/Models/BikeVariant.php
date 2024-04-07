<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BikeVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bike_size',
    ];

    function article() : BelongsTo {
        return $this->belongsTo(Article::class);
    }
    function bike() : BelongsTo {
        return $this->belongsTo(Bike::class);
    }
    function bookings() : HasMany {
        return $this->hasMany(Booking::class);
    }
}
