<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    protected array $data = [
        ['name' => 'Astratyan Dmitry', 'email' => 'astratyandmitry@gmail.com'],
        ['name' => 'Timoshenko Liliya', 'email' => 'astratyan.liliya@gmail.com'],
    ];

    public function run(): void
    {
        collect($this->data)->each(function (array $data) {
            User::query()->firstOrCreate([
                'email' => $data['email'],
            ], [
                'name' => $data['name'],
                'password' => $data['password'] ??= Hash::make('password'),
            ]);
        });
    }
}
