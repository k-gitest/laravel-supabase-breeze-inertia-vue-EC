<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Cart;
use App\Services\CartPriceService;
use Log;
use Exception;

class PaymentController extends Controller
{
  public function index(CartPriceService $cartPriceService): Response
  {
    $id = request()->user()->id;
    $result = Cart::with(['product.image'])->where('user_id', $id)->get();

    $totalPrice = $cartPriceService->getTotalPrices($result);
      
    Stripe::setApiKey(config('services.stripe.secret'));

    $warehouse_id = config('services.stripe.warehouse_id');

    try{
      $paymentIntent = DB::transaction(function () use ($totalPrice, $warehouse_id){
        return $paymentIntent = PaymentIntent::create([
            'amount' => round($totalPrice["total_price_including_tax"]), 
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
