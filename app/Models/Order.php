<?php

namespace App\Models;

use App\Models\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property string $code
 * @property int $collection_id
 * @property int $wholesaler_id
 *
 * @property \App\Models\Enums\OrderStatus $status
 * @property \App\Models\Collection $collection
 * @property \App\Models\Wholesaler $wholesaler
 * @property \App\Models\OrderItem[]|\Illuminate\Database\Eloquent\Collection $items
 */
final class Order extends Model
{
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Order $model) {
            $model->code = self::generateUniqueCode();
        });

        static::deleting(function (Order $model) {
            $model->items()->delete();
        });
    }

    private static function generateUniqueCode(): string
    {
        $code = Str::upper(Str::random(8));

        while (Order::query()->where('code', $code)->exists()) {
            $code = Str::upper(Str::random(8));
        }

        return $code;
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

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
