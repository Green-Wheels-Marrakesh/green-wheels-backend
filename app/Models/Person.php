<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'persons';
    protected $fillable = [
        'first_name',
        'last_name',
        'cin',
        'passport',
        'phone',
        'contact_email',
        'city',
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
    function user() : HasOne {
        return $this->hasOne(User::class, 'person_id');
    }
    function client() : HasOne {
        return $this->hasOne(Client::class, 'person_id');
    }
    function attachments() : HasMany {
        return $this->hasMany(PersonAttachment::class);
    }
}
