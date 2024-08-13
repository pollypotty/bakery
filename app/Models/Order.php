<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $delivery_type
 * @property string $payment_type
 * @property string $payment_total
 * @property string $delivery_date
 * @property string $order_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $data)
 */

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'delivery_type',
        'payment_type',
        'payment_total',
        'delivery_date',
        'order_status',
    ];
}
