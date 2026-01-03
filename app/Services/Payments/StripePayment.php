<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\Subscription;
use Error;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class StripePayment
{
    public function createCheckOutSession(Subscription $subscription): object
    {
        $stripe = app(StripeClient::class);
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $subscription->plan->name,
                        ],
                        'unit_amount' => $subscription->plan->price * 100,
                    ],
                    'quantity' => $subscription->period,
                ]
            ],
            'metadata' => [
                'subscription_id' => $subscription->id,
            ],
            'mode' => 'payment',
            'success_url' => route('payments.success', $subscription->id),
            'cancel_url' => route('payments.cancel', $subscription->id),
        ]);

        return $checkout_session;
    }
}
