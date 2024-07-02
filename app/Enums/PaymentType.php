<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASH = 'CASH';
    case STRIPE = 'STRIPE';
    case CARD = 'CARD';
}
