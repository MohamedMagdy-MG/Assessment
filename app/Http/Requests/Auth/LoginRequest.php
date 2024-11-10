<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Helper;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;  
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
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
