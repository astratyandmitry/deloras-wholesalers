<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $collection_id
 * @property string $sku
 * @property string|null $description
 * @property float $price_usd
 * @property string $image
 *
 * @property \App\Models\Collection $collection
 * @property \App\Models\ProductSize[]|\Illuminate\Database\Eloquent\Collection $sizes
 */
final class Product extends Model
{
    protected function casts(): array
    {
        return [
            'collection_id' => 'integer',
            'price_usd' => 'float',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }
}
