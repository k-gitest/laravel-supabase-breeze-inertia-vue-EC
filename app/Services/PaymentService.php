<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    public function createPaymentIntent($totalPrice, $warehouseId, $userId, $userEmail)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = DB::transaction(function () use ($totalPrice, $warehouseId, $userId, $userEmail) {
                return PaymentIntent::create([
                    'amount' => round($totalPrice["total_price_including_tax"]),
                    'currency' => 'jpy',
                    'payment_method' => 'pm_card_visa',
                    'metadata' => [
                        'user_id' => $userId,
                        'warehouse_id' => $warehouseId,
                        'email' => $userEmail,
                    ],
                ]);
            });
            Log::info('paymentIntent create succeeded');
            return $paymentIntent;
        } catch (Exception $e) {
            Log::error('PaymentIntent creation failed', ['error' => $e->getMessage()]);
            report($e);
            throw $e;
        }
    }
}
