<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Helper;

class VerifyOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;  
    }

    public function rules()
    {
        return [
            'email' => 'nullable|email|exists:users,email',
            'phone' => 'nullable|numeric|exists:users,phone',
            'otp_code' => 'required|numeric|exists:otps,otp_code', 
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.exists' => 'This phone number is not registered.',
            'otp_code.required' => 'OTP code is required.',
            'otp_code.numeric' => 'OTP code must be a number.',
            'otp_code.exists' => 'The provided OTP code is invalid.',
        ];
    }

   
    protected function failedValidation(Validator $validator)
    {
        $response = Helper::ResponseData(
            null,  
            'Validation failed',
            false,
            422,
            $validator->errors()  
        );

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
