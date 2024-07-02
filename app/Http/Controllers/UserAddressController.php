<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\UserCreateRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\NoReturn;

class UserAddressController extends Controller
{
    const COUNTRY = 1;

    #[NoReturn] public function getUserAddresses(): JsonResponse
    {
        $userAddresses = Auth::user()->user_addresses()->whereIn('address_type', [
            AddressType::DELIVERY->value,
            AddressType::BILLING_AND_DELIVERY->value,
        ])->get();

        return response()->json($userAddresses);
    }

    public function store(UserCreateRequest $request)
    {
        $requestData = $request->all();

        $address = new UserAddress();

        $address->user_id = Auth::id();
        $address->address_type = $requestData['type'];
        $address->country_id = self::COUNTRY;
        $address->zip_code = $requestData['zip'];
        $address->city = $requestData['city'];
        $address->line1 = $requestData['street'];
        $address->line2 = 'null';
        $address->building_number = $requestData['house'];
        $address->additional_information = $requestData['info'] ?? 'null';

        if ($address->save()) {
            return response()->json([$address], 200);
        }

        return response()->json(['error' => config('messages.address_save_error')], 500);
    }

}
