<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\AuthServiceInterface;
use App\Services\OtpService;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;

class AuthService implements AuthServiceInterface
{
    protected $otpService;
    protected $userService;

    public function __construct(OtpService $otpService, UserService $userService)
    {
        $this->otpService = $otpService;
        $this->userService = $userService;
    }

    public function register(array $data)
    {
        $user = $this->userService->createUser($data);
        $this->otpService->generateOtp($user);
        return $user;
       
    }

    public function login(array $data)
    {
        $user = $this->userService->getUserByCredentials($data['email'], $data['password']);

        if (!$user) {
            return Helper::ResponseData(null, 'Invalid credentials', false, 401);
        }
        if($user->is_verified === false){
            return Helper::ResponseData(null,'please verify your account first',false,400);
        }
        $token = $user->createToken('Assessment')->accessToken;
        $cacheKey = 'user_profile_' . $user->uid;
        $userData = Cache::remember($cacheKey, 86400, function () use ($user) {
            return $user->only(['uid', 'name', 'email', 'phone']);
        });
        
        return Helper::ResponseData([
            'user' => $userData,
            'token' => $token,
        ], 'User logged in successfully', true, 200);
    }

    public function sendOtp(array $data)
    {
        return $user = $this->userService->getUserByEmailOrPhone($data['email'], $data['phone']);

        if (!$user) {
            return Helper::ResponseData(null, 'User not found', false, 404);
        }

        $this->otpService->generateOtp($user);

        return Helper::ResponseData(null, 'OTP sent successfully', true, 200);
    }

    public function verifyOtp(array $data)
    {
        $user = $this->userService->getUserByEmailOrPhone($data['email'], $data['phone']);

        if (!$user) {
            return Helper::ResponseData(null, 'User not found', false, 404);
        }

        $data = $this->otpService->verifyOtp($user, $data['otp_code']);
        if ($data === false) {
            return Helper::ResponseData(null, 'Failed to verify otp', false, 400);
        }
        $user->is_verified = true;
        $user->save();

        $token = $user->createToken('Assessment')->accessToken;

        $cacheKey = 'user_profile_' . $user->uid;
        $userData = Cache::remember($cacheKey, 86400, function () use ($user) {
            return $user->only(['uid', 'name', 'email', 'phone']);
        });
        
        return Helper::ResponseData([
            'user' => $userData,
            'token' => $token,
        ], 'OTP verified successfully', true, 200);

    }

    public function forgotPassword(array $data)
    {
        return $this->sendOtp($data);
    }

    public function resetPassword(array $data)
    {
        $user = $this->userService->getUserByEmailOrPhone($data['email'], $data['phone']);

        if (!$user) {
            return Helper::ResponseData(null, 'User not found', false, 404);
        }

        $this->userService->resetPassword($user, $data['password']);
        $token = $user->createToken('Assessment')->accessToken;
        return Helper::ResponseData([
            'user' => $user->only(['uid', 'name', 'email', 'phone']),
            'token' => $token,
        ], 'Password reset successfully', true, 200);


    }
}
