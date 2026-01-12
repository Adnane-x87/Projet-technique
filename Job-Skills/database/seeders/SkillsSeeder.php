<?php

namespace Database\Seeders;

use App\Models\Skills;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (($handle = fopen(database_path('data/Skills.csv'), 'r')) !== false) {
            $header = fgetcsv($handle); 
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row); 
                Skills::create([
                    'name' => $data['name'],
                ]);
            }
            fclose($handle);
        }
    }
}
