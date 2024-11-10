<?php

namespace Database\Factories;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpFactory extends Factory
{
    
    protected $model = Otp::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'otp_code' => Str::random(6),  
            'expires_at' => Carbon::now()->addMinutes(5),  
            'used_at' => null, 
        ];
    }
}
