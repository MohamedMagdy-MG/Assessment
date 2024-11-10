<?php 

namespace App\Services;

use App\Helpers\Helper;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function createUser(array $data)
    {
        $existingUser = User::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();
        if ($existingUser) {
            return Helper::ResponseData(
                null, 
                'User already exists',
                false, 
                400, 
                null
            );
            
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'is_verified' => false
        ]);
    }

    public function getUserByCredentials($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function getUserByEmailOrPhone($email, $phone)
    {
        return User::where('email', $email)->orWhere('phone', $phone)->first();
    }

    public function resetPassword(User $user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }


}
