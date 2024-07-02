<?php

namespace App\Enums;

enum AddressType: string
{
    case BILLING = 'BILLING';
    case BILLING_AND_DELIVERY = 'BILLING_AND_DELIVERY';
    case DELIVERY = 'DELIVERY';
    case ONE_TIME = 'ONE_TIME';
}
