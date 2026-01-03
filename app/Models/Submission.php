<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = ['user_id', 'classwork_id', 'content', 'type',];

    public function classwork(): BelongsTo
    {
        return $this->belongsTo(Classwork::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
