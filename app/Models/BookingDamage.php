<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingDamage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'damage_type',
        'damage_date',
        'damage_estimated_price',
        'damage_description',
    ];
    protected $casts = [
        'damage_date' => 'datetime:Y-m-d',
    ];
    protected $appends = [
        'target_bike',
    ];

    protected function targetBike(): Attribute
    {
        return new Attribute(
            get: fn () => $this->booking_detail()
            ->join('bike_variants', 'booking_details.bike_variant_id', 'bike_variants.id')
            ->join('articles', 'bike_variants.article_id', 'articles.id')
            ->join('references', 'references.article_id', 'articles.id')
            ->value('generated_reference'),
        );
    }
    function booking_detail() : BelongsTo {
        return $this->belongsTo(BookingDetail::class);
    }
}
