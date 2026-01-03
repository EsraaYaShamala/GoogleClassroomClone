<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use SoftDeletes;

    public static string $disk = 'public';

    protected $fillable = [
        'name',
        'section',
        'subject',
        'room',
        'code',
        'cover_image_path',
        'theme',
        'user_id'
    ];

    protected $appends = [
        'cover_image_url',
    ];

    protected $hidden = [
        'cover_image_path',
        'deleted_at'
    ];
    protected static function booted()
    {
        static::addGlobalScope(new UserClassroomScope);
        static::observe(ClassroomObserver::class);
    }

    public function classworks(): HasMany
    {
        return $this->hasMany(Classwork::class, 'classroom_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'classroom_id', 'id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'classroom_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(
            User::class,
            'classroom_user',
            'classroom_id',
            'user_id',
            'id',
            'id'
        )->withPivot([
            'role',
            'created_at'
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->users()->wherePivot('role', '=', 'teacher');
    }

    public function students(): BelongsToMany
    {
        return $this->users()->wherePivot('role', '=', 'student');
    }

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class)->latest();
    }

    public static function uploadCoverImage($file)
    {
        $path = $file->store('/covers', [
            'disk' => static::$disk
        ]);

        return $path;
    }

    public static function deleteCoverImage($path)
    {
        if ($path || Storage::disk(Classroom::$disk)->exists($path)) {
            return Storage::disk(Classroom::$disk)->delete($path);
        }
    }

    //local scopes
    public function scopeActive(Builder $query)
    {
        $query->where('status', '=', 'active');
    }

    public function scopeRecent(Builder $query)
    {
        $query->orderBy('updated_at', 'desc');
    }

    public function join($user_id, $role = 'student')
    {
        $exists = $this->users()->where('id', '=', $user_id)->exists();
        if ($exists) {
            throw new Exception('You are already a member of this classroom');
        }

        return $this->users()->attach($user_id, ['role' => $role]);
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image_path) {
            return Storage::disk(static::$disk)->url($this->cover_image_path);
        }
        return 'https://placehold.co/800x200';
    }
}
