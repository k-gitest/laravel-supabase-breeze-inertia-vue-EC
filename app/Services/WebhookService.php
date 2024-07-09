<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Mail\OrderSuccessMail;
use Illuminate\Support\Facades\Mail;

class WebhookService
{
    public function handleWebhook($payload, $sig_header, $endpoint_secret)
    {
        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        return $event;
    }

    public function handleEvent($event)
    {
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            default:
                Log::warning('Unhandled event type: ' . $event->type);
                return response()->json(['error' => 'Unexpected event type'], 400);
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            DB::transaction(function () use ($paymentIntent) {
                $order = Order::create([
                    'user_id' => $paymentIntent->metadata->user_id,
                    'total_amount' => $paymentIntent->amount_received,
                    'currency' => $paymentIntent->currency,
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                ]);

                $cartItems = Cart::where('user_id', $paymentIntent->metadata->user_id)->get();

                foreach ($cartItems as $cartItem) {
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'user_id' => $cartItem->user_id,
                        'quantity' => $cartItem->quantity,
                        'price_excluding_tax' => $cartItem->product->price_excluding_tax,
                        'price_including_tax' => $cartItem->product->price_including_tax,
                    ]);

                    $stock = Stock::where('product_id', $cartItem->product_id)
                        ->where('warehouse_id', $paymentIntent->metadata->warehouse_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stock) {
                        if ($stock->quantity >= $cartItem->quantity) {
                            $stock->quantity -= $cartItem->quantity;
                            $stock->save();
                        } else {
                            throw new \Exception('在庫が不足しています。');
                        }
                    } else {
                        throw new \Exception('在庫が見つかりません。');
                    }
                }

                Cart::where('user_id', $paymentIntent->metadata->user_id)->delete();

                $user = $order->user;
                Mail::to($paymentIntent->metadata->email)->send(new OrderSuccessMail($order));
                
                Log::info('Payment succeeded', [
                    'user_id' => $paymentIntent->metadata->user_id,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount_received' => $paymentIntent->amount_received,
                    'currency' => $paymentIntent->currency,
                    'order_id' => $order->id,
                    'order_items' => $orderItem,
                ]);
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            $userId = $paymentIntent->metadata->user_id;

            $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
            if ($order) {
                $order->status = 'payment_failed';
                $order->save();
            }

            Log::error('Payment failed', [
                'user_id' => $userId,
                'payment_intent_id' => $paymentIntent->id,
                'error' => $paymentIntent->last_payment_error,
                'order_id' => $order ? $order->id : null,
            ]);
        } catch (\Exception $e) {
            Log::critical('Failed to process payment failure', [
                'user_id' => $paymentIntent->metadata->user_id,
                'payment_intent_id' => $paymentIntent->id,
                'exception' => $e->getMessage(),
            ]);
            report($e);
            return false;
        }
    }
}
