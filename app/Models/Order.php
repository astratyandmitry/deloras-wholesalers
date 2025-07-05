<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $code
 * @property int $collection_id
 * @property int $wholesaler_id
 *
 * @property \App\Models\Collection $collection
 * @property \App\Models\Wholesaler $wholesaler
 * @property \App\Models\OrderItem[]|\Illuminate\Database\Eloquent\Collection $items
 */
final class Order extends Model
{
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
