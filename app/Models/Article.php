<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'default_selling_price',
        'qty_notification_setting',
    ];

    function bike_variant() : HasOne {
        return $this->hasOne(BikeVariant::class);
    }
    function reference() : HasOne {
        return $this->hasOne(Reference::class);
    }
    function attachments() : HasMany {
        return $this->hasMany(ArticleAttachment::class);
    }
}
