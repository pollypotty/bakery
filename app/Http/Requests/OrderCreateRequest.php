<?php

namespace App\Http\Requests;

use App\Enums\DeliveryType;
use App\Enums\PaymentType;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            'addressId' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value != -1) {
                        $userId = auth()->id();
                        $address = DB::table('user_addresses')
                            ->where('id', $value)
                            ->where('user_id', $userId)
                            ->exists();

                        if (!$address) {
                            $fail('The selected address is invalid.');
                        }
                    }
                },
            ],
            'deliveryDate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $selectedDate = Carbon::parse($value);

                    $today = Carbon::now();

                    $minDate = $today->addDays(2);

                    if ($selectedDate <= $minDate) {
                        $fail('The ' . $attribute . ' must be a date later than two days from today.');
                    }
                },
            ],
            'deliveryType' => [
                'required',
                Rule::enum(DeliveryType::class),
            ],
            'paymentType' => [
                'required',
                Rule::enum(PaymentType::class),
            ],
            'orderItems' => [
                'required',
                'array'
            ],
            'orderItems.*.productId' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $product = DB::table('products')
                        ->where('id', $value)
                        ->exists();

                    if (!$product) {
                        $fail("The selected product doesn't exist.");
                    }
                },
            ],
            'orderItems.*.quantity' => [
                'required',
                'integer',
                'gt:0',
            ],
        ];
    }
}
