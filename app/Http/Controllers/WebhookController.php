<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class WebhookController extends CashierController
{
  public function handleWebhook(Request $request)
  {
  $payload = @file_get_contents('php://input');
  $sig_header = $request->header('Stripe-Signature');
  $endpoint_secret = config('services.stripe.webhook_secret'); 

  try {
    $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
  } catch (\UnexpectedValueException $e) {
    Log::error('Invalid payload: ' . $e->getMessage());
    return response()->json(['error' => 'Invalid payload'], 400);
  } catch (SignatureVerificationException $e) {
    Log::error('Invalid signature: ' . $e->getMessage());
    return response()->json(['error' => 'Invalid signature'], 400);
  }

  $this->handleEvent($event);

  return response()->json(['status' => 'success'], 200);
  }

  protected function handleEvent($event)
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
    DB::transaction(function () use ($paymentIntent)
    {
      $order = Order::create([
          'user_id' => $paymentIntent->metadata->user_id,
          'total_amount' => $paymentIntent->amount_received,
          'currency' => $paymentIntent->currency,
          'payment_intent_id' => $paymentIntent->id,
          'status' => $paymentIntent->status,
      ]);

      $cartItems = Cart::where('user_id', $paymentIntent->metadata->user_id)->get();

      foreach ($cartItems as $cartItem){
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'user_id' => $cartItem->user_id,
            'quantity' => $cartItem->quantity,
            'price_excluding_tax' => $cartItem->product->price_excluding_tax,
            'price_including_tax' => $cartItem->product->price_including_tax,
        ]);
      };

      Cart::where('user_id', $paymentIntent->metadata->user_id)->delete();
      
    });

  }

  protected function handlePaymentIntentFailed($paymentIntent)
  {
      // 支払い失敗の処理を行う
  }

}


