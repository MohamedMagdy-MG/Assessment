<?php

namespace App\Services;

use App\Interfaces\SmsServiceInterface;
use Illuminate\Support\Facades\Http;

class SmsService implements SmsServiceInterface
{
    protected $client;
    protected $twilioPhoneNumber;
    protected $sid;
    protected $authToken;

    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->authToken = env('TWILIO_AUTH_TOKEN');
        $this->twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');
    }

    public function sendSms(string $to, string $message)
    {
        Http::asForm()
            ->withBasicAuth($this->sid, $this->authToken)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                'To' => $to,
                'From' => $this->twilioPhoneNumber,
                'Body' => $message,
            ]);
            
    }
}
