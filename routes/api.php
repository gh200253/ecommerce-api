<?php
use App\Http\Controllers\Api\WebhookController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| 1. Public Routes 
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


// ... المسارات العامة القديمة
Route::post('/webhooks/payment', [WebhookController::class, 'handlePaymentWebhook']);
/*
|--------------------------------------------------------------------------
| 2. Protected Routes ( - Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // -- مسارات مشتركة (للعملاء والمديرين) --
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']); // السطر الجديد ده

    /*
    |--------------------------------------------------------------------------
    | 3. Admin Only Routes (مسارات الإدارة فقط)
    |--------------------------------------------------------------------------
    | Token + رتبته Admin
    */
    Route::middleware('role:admin')->group(function () {
        
        // إدارة الأقسام
        Route::post('/categories', [CategoryController::class, 'store']);
        
        // إدارة المنتجات
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        
    });

});

