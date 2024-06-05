<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_type',
        'product_model',
        'product_mark',
        'product_status',
        'qty_notification_setting',
    ];
    protected $appends = [
        'in_stock',
    ];

    protected function inStock(): Attribute
    {
        return new Attribute(
            get: fn () => $this->product_variants()->count(),
        );
    }
    function product_variants() : HasMany {
        return $this->hasMany(ProductVariant::class);
    }
}
