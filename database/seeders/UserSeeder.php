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
        collect($this->data)->each(function (array $userData) {
            User::query()->firstOrCreate([
                'email' => $userData['email'],
            ], [
                'name' => $userData['name'],
                'password' => $userData['password'] ??= Hash::make('password'),
            ]);
        });
    }
}
