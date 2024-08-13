<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{

    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function createPaymentIntent(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|integer|min:1',
            ]);

            $clientSecret = $this->paymentService->createPaymentIntent($validatedData['amount']);

            if ($clientSecret === null) {
                return response()->json([], 500);
            }

            return response()->json([
                'clientSecret' => $clientSecret,
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([], 500);
        }
    }
}
