<?php

namespace App\Http\Controllers\Api;
use App\Jobs\SendOrderEmailJob;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $user = auth()->user(); 
            $order = $this->orderService->checkout($user->id, $request->shipping_address, $request->items);
            SendOrderEmailJob::dispatch($order, $user);

            return $this->successResponse($order, 'تم إنشاء الطلب بنجاح', 201);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400); 
        }
    }


    public function index()
    {
        $userId = auth()->id(); 
        
        $orders = $this->orderService->getOrdersForUser($userId);
        
        if ($orders->isEmpty()) {
            return $this->successResponse([], 'لا توجد طلبات سابقة');
        }

        return $this->successResponse($orders, 'تم جلب سجل الطلبات بنجاح');
    }
}