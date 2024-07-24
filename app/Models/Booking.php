<?php

namespace App\Models;

use App\Enums\BookingTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date_end',
        'responsable',
        'partner',
        'pick_up_date',
        'pick_up_location',
        'booking_payment_status',
        'notes',
    ];
    protected $appends = [
        'booking_type',
    ];

    protected function bookingType(): Attribute
    {
        return new Attribute(
            get: fn () => $this->rental_booking()->exists() ? BookingTypeEnum::RENTAL() : BookingTypeEnum::TOUR(),
        );
    }
    function operation() : BelongsTo {
        return $this->belongsTo(Operation::class);
    }
    function client() : BelongsTo {
        return $this->belongsTo(Client::class);
    }
    function booking_details() : HasMany {
        return $this->hasMany(BookingDetail::class);
    }
    function booking_charges() : HasMany {
        return $this->hasMany(BookingCharge::class);
    }
    function booking_additionals() : HasMany {
        return $this->hasMany(BookingAdditional::class);
    }
    function rental_booking() : HasOne {
        return $this->hasOne(RentalBooking::class);
    }
    function tour_booking() : HasOne {
        return $this->hasOne(TourBooking::class);
    }
}
