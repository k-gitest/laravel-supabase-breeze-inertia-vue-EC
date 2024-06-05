<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;
use App\Models\Cart;

class PaymentController extends Controller
{
  public function index(): Response
  {

    $id = request()->user()->id;
    $result = Cart::with(['product.image'])->where('user_id', $id)->get();
  
    $total_price_excluding_tax = $result->sum(function ($item) {
      return $item->quantity * $item->product->price_excluding_tax;
    });
  
    $total_price_including_tax = $result->sum(function ($item) {
      return $item->quantity * $item->product->price_including_tax;
    });
    
    $totalPrice = [
      "total_price_excluding_tax" => $total_price_excluding_tax,
      "total_price_including_tax" => $total_price_including_tax,
    ];
      
    Stripe::setApiKey(config('services.stripe.secret'));

    $warehouse_id = config('services.stripe.warehouse_id');

    $paymentIntent = PaymentIntent::create([
        'amount' => round($total_price_including_tax),
        'currency' => 'jpy',
        'metadata' => [
           'user_id' => auth()->user()->id,
           'warehouse_id' => $warehouse_id,
        ],
    ]);
    
    return Inertia::render('EC/PaymentComponent', [
        'clientSecret' => $paymentIntent->client_secret,
        'totalPrice' => $totalPrice,
        'data' => $result,
    ]);
  }
  
}
