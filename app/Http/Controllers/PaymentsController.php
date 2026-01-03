<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\Payments\PaymentService;
use App\Services\Payments\StripePayment;
use Exception;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create(StripePayment $stripe, PaymentService $payment_service, Subscription $subscription)
    {
        // Check if subscription is pending before continue ..
        try {
            if ($subscription->status == 'pending') {
                $session = $stripe->createCheckOutSession($subscription);
                $payment_service->createFromStripeSession($session, $subscription);
                return redirect()->away($session->url);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $subscription = Subscription::findOrFail($request->subscription_id);
    }

    public function success(Request $request)
    {
        return view('payments.success');
    }

    public function cancel(Request $request)
    {
        return view('payments.cancel');
    }
}
