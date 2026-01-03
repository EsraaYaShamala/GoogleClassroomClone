<?php

namespace App\Models;

use App\Eumns\ClassworkType;
use App\Observers\ClassworkObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Classwork extends Model
{

    const TYPE_ASSIGNMENT = ClassworkType::ASSIGNMENT;
    const TYPE_MATERIAL = ClassworkType::MATERIAL;
    const TYPE_QUESTION = ClassworkType::QUESTION;

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';


    protected $fillable = [
        'classroom_id',
        'user_id',
        'topic_id',
        'title',
        'type',
        'description',
        'status',
        'options',
        'published_at'
    ];

    protected $casts = [
        'options' => 'json',
        'published_at' => 'datetime:Y-m-d',
        'type' => ClassworkType::class,
    ];

    protected static function booted()
    {
        static::observe(ClassworkObserver::class);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['search'] ?? '', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('title', 'LIKE', "%{$value}%")
                    ->orWhere('description', 'LIKE', "%{$value}%");
            });
        });
    }

    public function getPublishedDateAttribute()
    {
        if ($this->published_at) {
            return $this->published_at->format('Y-m-d');
        }
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classwork_user', 'classwork_id', 'user_id')
            ->withPivot(['grade', 'submitted_at', 'status', 'created_at'])
            ->using(ClassworkUser::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
