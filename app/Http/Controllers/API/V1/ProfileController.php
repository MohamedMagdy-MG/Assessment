<?php


namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileRequest;
use App\Services\ProfileService;
use App\Models\User;
use Exception;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show()
    {
        try {
            $profile = $this->profileService->getProfile();
            return Helper::ResponseData($profile, 'User profile retrieved successfully', true, 200);
        } catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred while retrieving the profile', false, 400);
        }
    }

    public function update(ProfileRequest $request)
    {
        try {
            $data = $this->profileService->updateProfile($request->validated());
            return Helper::ResponseData($data, 'User profile updated successfully', true, 200);
        }  catch (Exception $e) {
            return Helper::ResponseData(null, 'An error occurred while updating the profile', false, 400);
        }
    }
}
