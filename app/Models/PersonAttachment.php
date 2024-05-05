<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    function person() : BelongsTo {
        return $this->belongsTo(Person::class);
    }
    function attachment() : BelongsTo {
        return $this->belongsTo(Attachment::class);
    }
}
