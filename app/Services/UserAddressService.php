<?php

namespace App\Services;

use App\Enums\AddressType;
use App\Models\UserAddress;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserAddressService
{
    public function getUserAddresses()
    {
        return Auth::user()->user_addresses()->whereIn('address_type', [
            AddressType::DELIVERY->value,
            AddressType::BILLING_AND_DELIVERY->value,
        ])->get();
    }

    /**
     * @throws Exception
     */
    public function store(array $requestData)
    {
        try {
            $data = [
                'user_id' => Auth::id(),
                'address_type' => $requestData['type'],
                'zip_code' => $requestData['zip'],
                'city' => $requestData['city'],
                'line1' => $requestData['street'],
                'line2' => 'null',
                'building_number' => $requestData['house'],
                'additional_information' => $requestData['info'] ?? 'null',
            ];

            return UserAddress::create($data);
        } catch (Exception $e) {
            Log::error(config('messages.address_save_fail'), ['exception' => $e->getMessage()]);
            throw new Exception(config('messages.address_save_fail'));
        }
    }
}
