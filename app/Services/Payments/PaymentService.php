<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function createFromStripeSession($session, $subscription)
    {
        return Payment::create([
            'user_id' => Auth::id(),
            'subscription_id' => $subscription->id,
            'amount' => $subscription->price * 100,
            'currency' => 'usd',
            'payment_gateway' => 'stripe',
            'gateway_reference_id' => $session->id,
            'data' => $session,
        ]);
    }
}
