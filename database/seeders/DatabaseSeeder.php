<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure admin user exists
        User::updateOrCreate(
            ['name' => 'admin'],
            [
                'password' => \Illuminate\Support\Facades\Hash::make('senhaBOLAOCOPA26#'),
                'is_admin' => true,
            ]
        );
        $this->call([
            GameSeeder::class,
        ]);
    }
}
