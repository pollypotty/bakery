<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressCreateRequest;
use App\Services\UserAddressService;
use Illuminate\Http\JsonResponse;

class UserAddressController extends Controller
{
    protected UserAddressService $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    public function getUserAddresses(): JsonResponse
    {
        return response()->json($this->userAddressService->getuserAddresses());
    }

    public function store(UserAddressCreateRequest $request): JsonResponse
    {
        $requestData = $request->all();

        try {
            $address = $this->userAddressService->store($requestData);
            return response()->json($address, 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
