<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'original_reference',
        'generated_reference',
    ];

    function article() : BelongsTo {
        return $this->belongsTo(Article::class);
    }
}
