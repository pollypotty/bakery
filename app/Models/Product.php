<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property int $prepare_days
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

class Product extends Model
{
    use HasFactory;

    public function product_images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
