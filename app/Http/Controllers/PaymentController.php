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
use Log;
use Illuminate\Support\Facades\DB;

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

    try{
      $paymentIntent = DB::transaction(function () use ($total_price_including_tax, $warehouse_id){
        return $paymentIntent = PaymentIntent::create([
            'amount' => round($total_price_including_tax), 
            'currency' => 'jpy',
            'payment_method' => 'pm_card_visa',
            'metadata' => [
               'user_id' => auth()->user()->id,
               'warehouse_id' => $warehouse_id,
            ],
        ]);
      });
      Log::info('paymentIntent create succeeded');
    }
    catch(\Exception $e){
      Log::error('Failed to create paymentIntent.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to create paymentIntent. Please try again.']);
    }
    
    return Inertia::render('EC/PaymentComponent', [
        'clientSecret' => $paymentIntent->client_secret,
        'totalPrice' => $totalPrice,
        'data' => $result,
    ]);
  }
  
}
