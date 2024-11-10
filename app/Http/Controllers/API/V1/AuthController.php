<?php 

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\AuthService;
use Exception;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $this->authService->register($request->validated());
            return Helper::ResponseData($data, 'User registered successfully', true, 201);
        } catch (ValidationException $e) {
            return Helper::ResponseData(null, $e->errors(), false, 400);
        }catch (Exception $e) {
            return Helper::ResponseData(null, $e->getMessage(), false, 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->authService->login($request->validated());
        } catch (InvalidCredentialsException $e) {
            return Helper::ResponseData(null, $e->getMessage(), false, 401);
        } catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred during login', false, 400);
        }
    }

    public function sendOtp(SendOtpRequest $request)
    {
        
       
        return $request;
        try {
            return $this->authService->sendOtp($request->validated());
        } catch (UserNotFoundException $e) {
            return Helper::ResponseData(null, $e->getMessage(), false, 404);
        } catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred while sending OTP', false, 400);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            return $this->authService->verifyOtp($request->validated());
        } catch (Exception $e) {
            return Helper::ResponseData(null, $e->getMessage(), false, 400);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
           return $this->authService->forgotPassword($request->validated());
        } catch (UserNotFoundException $e) {
            return Helper::ResponseData(null, $e->getMessage(), false, 404);
        } catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred while processing the password reset', false, 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            return $this->authService->resetPassword($request->validated());
        } catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred during password reset', false, 400);
        }
    }
}
