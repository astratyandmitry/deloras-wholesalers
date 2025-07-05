<?php

namespace App\Models;

use App\Models\Enums\OrderQuantityType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $order_id
 * @property int $product_id
 * @property int $size_id
 * @property int $quantity
 * @property bool $fulfilled
 *
 * @property \App\Models\Enums\OrderQuantityType $quantity_type
 * @property \App\Models\Order $order
 * @property \App\Models\Product $product
 * @property \App\Models\ProductSize $size
 */
final class OrderItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'quantity_type' => OrderQuantityType::class,
            'fulfilled' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class);
    }
}
