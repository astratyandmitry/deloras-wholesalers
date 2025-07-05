<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property string $code
 * @property int $collection_id
 * @property int $wholesaler_id
 * @property bool $fulfilled
 *
 * @property \App\Models\Collection $collection
 * @property \App\Models\Wholesaler $wholesaler
 * @property \App\Models\OrderItem[]|\Illuminate\Database\Eloquent\Collection $items
 */
final class Order extends Model
{
    protected function casts(): array
    {
        return [
            'fulfilled' => 'boolean',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Order $model) {
            $model->code = self::generateUniqueCode();
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
