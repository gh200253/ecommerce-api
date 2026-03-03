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
        // هنبدأ الـ Transaction
        return DB::transaction(function () use ($userId, $totalAmount, $shippingAddress, $items) {
            
            // 1. إنشاء الطلب الأساسي
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()), // رقم مميز وعشوائي
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'shipping_address' => $shippingAddress,
            ]);

            // 2. إضافة تفاصيل الطلب وخصم الكمية
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // خصم الكمية المباعة من جدول المنتجات
                Product::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }

            // إرجاع الأوردر مع تفاصيله
            return $order->load('items'); 
        });
    }

    public function getUserOrders($userId)
    {
        return Order::where('user_id', $userId)
                    ->with('items.product') // Eager Loading لحل مشكلة N+1
                    ->orderBy('created_at', 'desc') // أحدث الطلبات تظهر الأول
                    ->get();
    }
}