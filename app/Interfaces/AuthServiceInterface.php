<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function register(array $data);
    public function login(array $data);
    public function sendOtp(array $data);
    public function verifyOtp(array $data);
    public function forgotPassword(array $data);
    public function resetPassword(array $data);
}
