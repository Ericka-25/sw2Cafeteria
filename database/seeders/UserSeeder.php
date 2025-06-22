<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Jessica',
            'email' => 'jessi@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Gerente');

        User::factory()->create([
            'name' => 'Ericka',
            'email' => 'ericka@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Cajero');

        User::factory()->create([
            'name' => 'Isela',
            'email' => 'isela@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Cajero');
        
        User::factory()->create([
            'name' => 'Maria',
            'email' => 'maria@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('Cajero');
    }
}
