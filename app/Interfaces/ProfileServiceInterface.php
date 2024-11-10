<?php

namespace App\Interfaces;


interface ProfileServiceInterface
{
    public function getProfile();
    public function updateProfile(array $data);
}
