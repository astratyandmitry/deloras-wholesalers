<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    protected array $products = [
        [
            'sku' => 'Q63514',
            'description' => 'Юбка',
        ],
        [
            'sku' => 'Z63303S',
            'description' => 'Водолазка',
        ],
        [
            'sku' => 'M63758',
            'description' => 'Свитшот',
        ],
        [
            'sku' => 'Y22269',
            'description' => 'Джинсы',
        ],
        [
            'sku' => 'Z19666',
            'description' => 'Футболка',
        ],
        [
            'sku' => 'H21889',
            'description' => 'Пальто',
        ],
        [
            'sku' => 'K55365',
            'description' => 'Пальто',
        ],
    ];

    public function run(): void
    {
        /** @var \App\Models\Collection $collection */
        $collection = Collection::factory()->create();

        collect($this->products)->each(function (array $data, int $index) use ($collection) {
            $image = 'product-'.($index + 1).'.webp';

            /** @var \App\Models\Product $product */
            $product = Product::query()->updateOrCreate([
                'sku' => $data['sku'],
            ], [
                'collection_id' => $collection->id,
                'description' => $data['description'],
                'price_usd' => rand(10, 30),
                'image' => "/tmp_images/{$image}",
            ]);

            $sizes = $this->getRandomSizes(rand(1, 3));

            $product->sizes()->createMany(
                collect($sizes)->map(fn (string $size) => ['name' => $size])
            );
        });
    }

    private function getRandomSizes(int $count): array
    {
        $sizes = [
            '80-92',
            '86-98',
            '92-104',
            '98-110',
            '104-116',
            '110-122',
            '116-128',
            '120-130',
            '122-134',
            '124-136',
            '128-140',
            '130-142',
            '132-144',
            '134-146',
            '136-148',
            '138-150',
            '140-152',
            '146-158',
            '152-164',
            '160-176',
        ];

        shuffle($sizes);

        return array_slice($sizes, 0, min($count, count($sizes)));
    }
}
