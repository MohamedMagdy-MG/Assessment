<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Helper;

class ForgotPasswordRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email is not registered.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.exists' => 'This phone number is not registered.',
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
