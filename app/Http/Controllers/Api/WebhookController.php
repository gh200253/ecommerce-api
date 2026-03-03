<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePaymentWebhook(Request $request)
    {
        Log::info('Payment Webhook Received: ', $request->all());

        $orderNumber = $request->input('order_number');
        $status = $request->input('status');

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === 'success') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing' 
            ]);
        } elseif ($status === 'failed') {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);
        }

        return response()->json(['message' => 'Webhook Handled Successfully'], 200);
    }
}