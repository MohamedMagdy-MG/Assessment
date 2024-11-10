<?php 

namespace App\Interfaces;

interface SmsServiceInterface
{
    public function sendSms(string $to, string $message);
}
