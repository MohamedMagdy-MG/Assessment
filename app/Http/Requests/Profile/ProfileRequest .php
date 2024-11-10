<?php 


namespace App\Http\Requests\Profile;

use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',  
            'email' => 'nullable|email|unique:users,email,' . auth()->guard('api')->user()->id,
            'phone' => 'nullable|numeric|unique:users,phone,' . auth()->guard('api')->user()->id,
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
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
