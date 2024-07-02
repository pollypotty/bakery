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
 * @property int $product_id
 * @property int $quantity
 * @property string $price_per_item
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */


class OrderItem extends Model
{
    use HasFactory;
}
