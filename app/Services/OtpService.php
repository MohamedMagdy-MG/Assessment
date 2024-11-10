<?php 


namespace App\Services;

use App\Interfaces\OtpServiceInterface;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Log;

class OtpService implements OtpServiceInterface
{
  
    public function generateOtp(User $user)
    {
        $otpCode = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
            'used_at' => null
        ]);
        $smsService = new SmsService();
        try {
            $message = "Your OTP code is {$otpCode}.";
            $smsService->sendSms($user->phone, $message);
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }

    }
  


    
    public function verifyOtp(User $user, string $otpCode)
    {
        $otp = Otp::where('user_id', $user->id)
                  ->where('otp_code', $otpCode)
                  ->whereNull('used_at')
                  ->where('expires_at', '>', Carbon::now())
                  ->first();

        if (!$otp) {
            return false;
        }
       
        $otp->used_at = Carbon::now();
        $otp->save();
    }
}
