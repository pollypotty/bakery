<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case REJECTED = 'REJECTED';
    case DELETED = 'DELETED';
    case DELIVERED = 'DELIVERED';
}
