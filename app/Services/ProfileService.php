<?php
namespace App\Services;

use App\Interfaces\ProfileServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ProfileService implements ProfileServiceInterface
{
    public function getProfile()
    {
        $user = auth()->guard('api')->user();
        $cacheKey = 'user_profile_' . $user->uid;
        return Cache::get($cacheKey, function () use ($user) {
            return $user->only(['uid', 'name', 'email', 'phone']);
        });
        
    }

    public function updateProfile(array $data)
    {
        $user = auth()->guard('api')->user();

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
        ]);
        
        $cacheKey = 'user_profile_' . $user->uid;
        if(Cache::has($cacheKey)) Cache::forget($cacheKey);
        return Cache::remember($cacheKey, 86400, function () use ($user) {
            return $user->only(['uid', 'name', 'email', 'phone']);
        });
    }
}
