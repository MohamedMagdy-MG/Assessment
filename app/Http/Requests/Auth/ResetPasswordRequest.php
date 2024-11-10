<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;

class ResetPasswordRequest extends FormRequest
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
            'password' => [
                'required',
                'string',
                'min:8', 
                'confirmed', 
            ],
            'password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.exists' => 'This phone number is not registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters.',
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
