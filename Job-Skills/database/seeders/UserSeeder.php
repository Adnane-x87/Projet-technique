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
            $header = fgetcsv($handle); 
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row); 
                User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make($data['password']),
                        'is_admin' => (bool)($data['is_admin'] ?? false),
                    ]
                );
            }
            fclose($handle);
        }
    }
}
