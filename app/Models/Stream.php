<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Stream extends Model
{
    use HasUuids;

    protected $fillable = ['classroom_id', 'user_id', 'link', 'content'];

    protected static function booted() {}

    public function getUpdatedAtColumn() {}
    // public function setUpdatedAt($value)
    // {
    //     return $this;
    // }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
