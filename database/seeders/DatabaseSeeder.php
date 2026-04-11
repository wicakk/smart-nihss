<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin user
        User::firstOrCreate(
            ['email' => 'admin@nihss.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // NIHSS form structure
        $this->call(NihssFormSeeder::class);

        // Demo patients (optional)
        $this->call(DemoPatientSeeder::class);
    }
}
