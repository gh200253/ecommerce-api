<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // confirmed يعني لازم يبعت password_confirmation
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $result = $this->authService->registerUser($request->all());

        return $this->successResponse($result, 'تم إنشاء الحساب بنجاح', 201);
    }

  public function login(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'email' => 'required|email',
          'password' => 'required|string',
      ]);

      if ($validator->fails()) {
          return $this->errorResponse($validator->errors()->first(), 422);
      }

      $result = $this->authService->loginUser($request->all());

      if (!$result) {
          return $this->errorResponse('بيانات الدخول غير صحيحة', 401); // 401 Unauthorized
      }

      return $this->successResponse($result, 'تم تسجيل الدخول بنجاح');
  }

  public function logout(Request $request)
  {
      $this->authService->logoutUser($request->user());
      return $this->successResponse(null, 'تم تسجيل الخروج بنجاح');
  }
}