<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'default_selling_price',
        'buying_price',
        'qty_notification_setting',
    ];
    protected $appends = [
        'attachment_url',
    ];

    protected function attachmentUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                $lastAttachment = $this->attachments()->get()->last();
                $attachmentUrl = '';
                if ($lastAttachment) {
                    $attachmentUrl = Storage::url($lastAttachment->attachment->attachment_path);
                }
                return $attachmentUrl;
            },
        );
    }
    function bike_variant() : HasOne {
        return $this->hasOne(BikeVariant::class);
    }
    function product_variant() : HasOne {
        return $this->hasOne(ProductVariant::class);
    }
    function reference() : HasOne {
        return $this->hasOne(Reference::class);
    }
    function attachments() : HasMany {
        return $this->hasMany(ArticleAttachment::class);
    }
}
