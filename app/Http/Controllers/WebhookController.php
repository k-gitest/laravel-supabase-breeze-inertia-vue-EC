<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Http\Request;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;

class WebhookController extends CashierController
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function handleWebhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        $event = $this->webhookService->handleWebhook($payload, $sig_header, $endpoint_secret);

        if (is_a($event, 'Illuminate\Http\JsonResponse')) {
            return $event;
        }

        $this->webhookService->handleEvent($event);

        return response()->json(['status' => 'success'], 200);
    }

}


