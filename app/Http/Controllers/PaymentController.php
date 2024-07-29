<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|integer|min:1',
            ]);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $validatedData['amount'] * 100,
                'currency' => 'eur',
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (ApiErrorException|\Exception $e) {
            Log::error($e);

            return response()->json([], 500);
        }
    }
}
