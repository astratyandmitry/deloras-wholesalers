<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $city
 * @property string $phone
 *
 * @property \App\Models\Order[]|\Illuminate\Database\Eloquent\Collection $orders
 */
final class Wholesaler extends Model
{
    use HasFactory;

    public function orders(): HasMany
    {
        $this->hasMany(Order::class);
    }
}
