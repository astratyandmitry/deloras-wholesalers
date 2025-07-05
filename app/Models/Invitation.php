<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $collection_id
 * @property int $wholesaler_id
 * @property string $token
 * @property string $whatsapp_url
 *
 * @property \App\Models\Collection $collection
 * @property \App\Models\Wholesaler $wholesaler
 */
final class Invitation extends Model
{
    public static function boot(): void
    {
        parent::boot();

        self::creating(function (Invitation $model) {
            $model->token = self::generateUniqueCode();
        });
    }

    private static function generateUniqueCode(): string
    {
        $token = Str::upper(Str::random(8));

        while (self::query()->where('token', $token)->exists()) {
            $token = Str::upper(Str::random(8));
        }

        return $token;
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public static function make(int $wholesalerId, int $collectionId): Invitation
    {
        return Invitation::query()->firstOrCreate([
            'wholesaler_id' => $wholesalerId,
            'collection_id' => $collectionId,
        ]);
    }

    public function getWhatsappUrlAttribute(): string
    {
        $phone = preg_replace('/\D+/', '', $this->wholesaler->phone);

        $url = route('order.checkout', [$this->collection, 't' => $this->token]);

        $text = urlencode("Deloras. Оформите оптовую заявку на коллекцию: {$this->collection->name}\n\n{$url}");

        return "https://api.whatsapp.com/send/?phone={$phone}&text={$text}";
    }
}
