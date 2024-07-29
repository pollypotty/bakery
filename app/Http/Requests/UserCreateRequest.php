<?php

namespace App\Http\Requests;

use App\Enums\AddressType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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

            'zip' => 'required|string|regex:/^[1-9][0-9]{3}$/',
            'city' => 'required|string|max:40',
            'street' => 'required|string|max:40',
            'house' => 'required|string|min:1|max:200',
            'info' => 'string|nullable|max:100',
            'type' => [Rule::enum(AddressType::class)],

        ];
    }
}
