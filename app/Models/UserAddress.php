<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property string $address_type
 * @property int $country_id
 * @property string $zip_code
 * @property string $city
 * @property string $line1
 * @property string $line2
 * @property string $building_number
 * @property string $additional_information
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $requestData)
 */

class UserAddress extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'address_type',
        'zip_code',
        'city',
        'line1',
        'line2',
        'building_number',
        'additional_information',
    ];
}
