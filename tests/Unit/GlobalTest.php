<?php

namespace Tests\Unit;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GlobalTest extends TestCase
{
    public function test_register()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '+201032432483',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    public function test_send_otp()
    {
        $response = $this->postJson('/api/v1/send-otp', [
            'phone' => null,
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(200);
    }

    public function test_verify_otp()
    {
        $otp_code = Otp::whereHas('user', function ($query) {
            $query->where('email', 'test@test.com');
        })->value('otp_code');

        $response = $this->postJson('/api/v1/verify-otp', [
            'phone' => null,
            'email' => 'test@test.com',
            'otp_code' => $otp_code,
        ]);

        $response->assertStatus(200);
    }

    public function test_login()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(200);
        $user = $response->json('data.user');
        $cacheKey = 'user_profile_' . $user['uid'];
        $this->assertTrue(Cache::has($cacheKey));
        $cachedUser = Cache::get($cacheKey);
        $this->assertEquals($cachedUser['uid'], $user['uid']);
        $this->assertEquals($cachedUser['name'], $user['name']);
        $this->assertEquals($cachedUser['email'], $user['email']);
        $this->assertEquals($cachedUser['phone'], $user['phone']);
    }

    public function test_invalid_login()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@test.com',
            'password' => '000000000000000',
        ]);

        $response->assertStatus(401);
    }

    public function test_oauth_token()
    {
        $user = User::where('email', 'test@test.com')->first();
        $token = $user->createToken('Test Token')->accessToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/profile');
        $response->assertStatus(200);
    }

    public function test_without_token()
    {
        $response = $this->getJson('/api/v1/profile');
        $response->assertStatus(401);
    }

}
