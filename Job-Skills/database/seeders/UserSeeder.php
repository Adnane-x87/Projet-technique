<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (($handle = fopen(database_path('data/User.csv'), 'r')) !== false) {
            $header = fgetcsv($handle); // Reads column names: ['id', 'name', 'email', 'password']
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row); // Maps column names to values
                User::firstOrCreate(
                    ['email' => $data['email']], // Check if user with this email already exists
                    [
                        'name' => $data['name'],
                        'password' => Hash::make($data['password']), // Securely hashes the password
                    ]
                );
            }
            fclose($handle);
        }
    }
}
