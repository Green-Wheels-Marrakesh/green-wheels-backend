<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleAttachment extends Model
{
    use HasFactory, SoftDeletes;

    function article() : BelongsTo {
        return $this->belongsTo(Article::class);
    }
    function attachment() : BelongsTo {
        return $this->belongsTo(Attachment::class);
    }
}
