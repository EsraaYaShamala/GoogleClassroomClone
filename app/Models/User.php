<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function classrooms(): BelongsToMany
    {
        return $this->BelongsToMany(
            Classroom::class,
            'classroom_user',
            'user_id',
            'classroom_id',
            'id',
            'id'
        )->withPivot([
            'role',
            'created_at'
        ]);
    }

    public function createdClassrooms(): HasMany
    {
        return $this->hasMany(Classroom::class, 'user_id');
    }

    public function classworks(): BelongsToMany
    {
        return $this->belongsToMany(Classwork::class, 'classwork_user', 'user_id', 'classwork_id')
            ->withPivot(['grade', 'submitted_at', 'status', 'created_at'])
            ->using(ClassworkUser::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class)->withDefault();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }

    public function routeNotificationForVonage($notification = null)
    {
        return '+9725';
    }
    public function routeNotificationForHadara($notification = null)
    {
        return '+972599195088';
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'Notifications' . $this->id;
    }

    public function preferredLocale()
    {
        return $this->profile->locale;
    }
}
