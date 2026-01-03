<?php

namespace App\Models;

use App\concerns\HasPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasPrice;

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'plan_feature')->withPivot(['feature_value']);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }
}
