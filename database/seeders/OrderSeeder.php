<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Enums\OrderQuantityType;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Wholesaler;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

final class OrderSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \App\Models\Collection $collection */
        $collection = Collection::query()->first();

        Wholesaler::query()->get()
            ->each(function (Wholesaler $wholesaler) use ($collection) {
                /** @var \App\Models\Order $order */
                $order = Order::query()->create([
                    'wholesaler_id' => $wholesaler->id,
                    'collection_id' => $collection->id,
                ]);

                Product::query()->inRandomOrder()->limit(rand(2, 3))->get()
                    ->each(function (Product $product) use ($order, $collection, $wholesaler) {
                        $product->sizes()->limit(rand(1, 3))->get()
                            ->each(function (ProductSize $size) use ($product, $order) {
                                $order->items()->create([
                                    'product_id' => $product->id,
                                    'size_id' => $size->id,
                                    'quantity' => rand(1, 10),
                                    'quantity_type' => Arr::random(OrderQuantityType::toArray()),
                                ]);
                            });
                    });
            });
    }
}
