<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_address_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $data)
 */


class DeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_address_id',
    ];
}
