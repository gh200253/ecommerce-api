<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Product;
use Exception;

class OrderService
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function checkout($userId, $shippingAddress, $cartItems)
    {
        $totalAmount = 0;
        $processedItems = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem['product_id']);
            if (!$product || $product->stock_quantity < $cartItem['quantity']) {
                if ($product->stock_quantity == 0) {
                    throw new Exception("عذراً، المنتج {$product->name} نفد من المخزون تماماً.");
                } else {
                    throw new Exception("المنتج {$product->name} غير متوفر بالكمية المطلوبة. المتاح حالياً هو {$product->stock_quantity} فقط.");
                }
            }

            $totalAmount += ($product->price * $cartItem['quantity']);
            
            $processedItems[] = [
                'product_id' => $product->id,
                'quantity' => $cartItem['quantity'],
                'unit_price' => $product->price,
            ];
        }

        return $this->orderRepo->createOrder($userId, $totalAmount, $shippingAddress, $processedItems);
    }

    public function getOrdersForUser($userId)
    {
        return $this->orderRepo->getUserOrders($userId);
    }
}