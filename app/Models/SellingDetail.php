<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'selling_price',
    ];

    function article() : BelongsTo {
        return $this->belongsTo(Article::class);
    }
    function selling() : BelongsTo {
        return $this->belongsTo(Selling::class);
    }
}
