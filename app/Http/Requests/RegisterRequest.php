<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|email|max:60|unique:users',
            'firstName' => 'required|string|max:40|regex:/^[a-zA-Z\s-]+$/',
            'lastName'  => 'required|string|max:40|regex:/^[a-zA-Z\s-]+$/',
            'password'  => 'required|min:6|confirmed',
        ];
    }
}
