<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Selling extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    function operation() : BelongsTo {
        return $this->belongsTo(Operation::class);
    }
    function client() : BelongsTo {
        return $this->belongsTo(Client::class);
    }
    function selling_details() : HasMany {
        return $this->hasMany(SellingDetail::class);
    }
}
