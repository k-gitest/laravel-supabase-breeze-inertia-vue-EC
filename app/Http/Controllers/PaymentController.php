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
use App\Services\CartService;
use App\Services\PaymentService;
use Log;
use Exception;

class PaymentController extends Controller
{
  protected $paymentService;

  public function __construct(PaymentService $paymentService)
  {
      $this->paymentService = $paymentService;
  }
  
  public function index(CartService $cartService): Response|bool
  {
    $userId = request()->user()->id;
    $userEmail = auth()->user()->email;
    $result = Cart::with(['product.image'])->where('user_id', $userId)->get();

    $totalPrice = $cartService->getTotalPrices($result);

    $warehouseId = config('services.stripe.warehouse_id');

    try {
        $paymentIntent = $this->paymentService->createPaymentIntent($totalPrice, $warehouseId, $userId, $userEmail);
    } catch (Exception $e) {
        return false;
    }

    return Inertia::render('EC/PaymentComponent', [
        'clientSecret' => $paymentIntent->client_secret,
        'totalPrice' => $totalPrice,
        'data' => $result,
    ]);
    
  }
  
}
