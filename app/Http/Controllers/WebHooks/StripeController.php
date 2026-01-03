<?php

namespace App\Http\Controllers\WebHooks;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function __invoke(Request $request, StripeClient $stripe_client)
    {
        $endpoint_secret = 'whsec_1f5a4ea1a2a3c6c87411a5ccdfdb4bf1066f74ad92e0c62c62c9299a5ca09a32';
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            echo '⚠️  Webhook error while validating signature.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Payment::where('gateway_reference_id', $session->id)->update([
                    'gateway_reference_id' => $session->payment_intent,
                ]);
                Log::info('From checkout.session.completed: ' . $session->payment_intent);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $payment = Payment::where('gateway_reference_id', $paymentIntent->id)->first();
                Log::info('PaymentIntent ID from Stripe: ' . $paymentIntent->id);
                if ($payment) {
                    $payment->forceFill([
                        'status' => 'completed',
                    ])->save();

                    $payment->subscription()->update([
                        'status' => 'active',
                        'expires_at' => now()->addMonths($payment->subscription->period),
                    ]);
                }
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        http_response_code(200);
    }
}
