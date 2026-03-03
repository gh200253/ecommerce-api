<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function getUserOrders($userId);
    public function createOrder($userId, $totalAmount, $shippingAddress, $items);
}