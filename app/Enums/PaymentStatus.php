<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case DUE = 'DUE';
    case UNCONFIRMED = 'UNCONFIRMED';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
}
