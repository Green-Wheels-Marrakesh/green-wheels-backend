<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'charge_date',
        'charge_price',
        'charge_description',
    ];
    protected $casts = [
        'charge_date' => 'datetime:d-m-Y',
    ];
}
