<?php

namespace App\Interfaces;

use App\Models\User;

interface UserServiceInterface
{
    public function createUser(array $data);
    public function getUserByCredentials($email, $password);
    public function getUserByEmailOrPhone($email, $phone);
    public function resetPassword(User $user, $password);
}
