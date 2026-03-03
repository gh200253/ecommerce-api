<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder($userId, $totalAmount, $shippingAddress, $items)
    {
        return DB::transaction(function () use ($userId, $totalAmount, $shippingAddress, $items) {
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                Product::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }

            return $order->load('items'); 
        });
    }

    public function getUserOrders($userId)
    {
        return Order::where('user_id', $userId)
                    ->with('items.product')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}