<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'min_price', 'max_price']);
        
        $products = $this->productService->getAllProducts($filters);
        
        return $this->successResponse($products, 'تم جلب المنتجات بنجاح');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', 
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $product = $this->productService->createProduct($request->all());

        return $this->successResponse($product, 'تم إضافة المنتج بنجاح', 201);
    }
    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        if (!$product) return $this->errorResponse('المنتج غير موجود', 404);
        return $this->successResponse($product, 'تم جلب المنتج بنجاح');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) return $this->errorResponse($validator->errors()->first(), 422);

        $product = $this->productService->updateProduct($id, $request->all());
        if (!$product) return $this->errorResponse('المنتج غير موجود', 404);

        return $this->successResponse($product, 'تم تعديل المنتج بنجاح');
    }

    public function destroy($id)
    {
        $deleted = $this->productService->deleteProduct($id);
        if (!$deleted) return $this->errorResponse('المنتج غير موجود', 404);
        return $this->successResponse(null, 'تم حذف المنتج بنجاح');
    }

}