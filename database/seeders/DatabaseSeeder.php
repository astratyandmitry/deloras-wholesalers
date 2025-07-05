<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\User;

use App\Models\Wholesaler;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Wholesaler::factory()->count(10)->create();

        $this->call([
            ProductSeeder::class,
            UserSeeder::class,
        ]);
    }
}
