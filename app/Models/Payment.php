<?php

namespace App\Models;

use App\concerns\HasPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasPrice;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount',
        'currency',
        'payment_gateway',
        'gateway_reference_id',
        'status',
        'data'
    ];
    protected $casts = [
        'data' => 'json',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
