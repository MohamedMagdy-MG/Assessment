<?php

namespace App\Interfaces;

use App\Models\User;

interface OtpServiceInterface
{
    public function generateOtp(User $user);
    public function verifyOtp(User $user, string $otpCode);
}
