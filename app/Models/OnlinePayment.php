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
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */


class OnlinePayment extends Model
{
    use HasFactory;
}
