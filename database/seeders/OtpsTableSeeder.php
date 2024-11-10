<?php

namespace Database\Seeders;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Database\Seeder;

class OtpsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();        
        foreach ($users as $user) {
            Otp::factory()->create([
                'user_id' => $user->id,  
            ]);

        }
    }
}
