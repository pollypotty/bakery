<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createPaymentIntent($amount): ?string
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'eur',
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);

            return $paymentIntent->client_secret;
        } catch (ApiErrorException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
