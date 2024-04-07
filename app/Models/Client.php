<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    function person() : BelongsTo {
        return $this->belongsTo(Person::class, 'person_id');
    }
    function bookings() : HasMany {
        return $this->hasMany(Booking::class);
    }
}
