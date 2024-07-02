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
 */

class Order extends Model
{
    use HasFactory;
}
