<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\OtpServiceInterface;
use App\Interfaces\SmsServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\SmsService;
use App\Services\UserService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(OtpServiceInterface::class, OtpService::class);
        $this->app->bind(SmsServiceInterface::class, SmsService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    
    public function boot(): void
    {
        $this->configureRateLimiting();

    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('otp', function ($request) {
            return Limit::perHour(5)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return Helper::ResponseData(
                        null, 
                        'Too many OTP requests. Please try again later.',
                        false,
                        429
                    );
                });
        });
        
    }
}
