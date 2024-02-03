<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'salary',
        'start_date',
    ];
    protected $with = [
        'user',
    ];

    function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
