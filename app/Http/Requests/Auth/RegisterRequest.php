<?php

namespace App\Http\Requests\Auth;

use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',  
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|numeric|unique:users,phone',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.unique' => 'This phone number is already registered.',
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
