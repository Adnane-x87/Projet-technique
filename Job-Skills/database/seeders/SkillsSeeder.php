<?php

namespace Database\Seeders;

use App\Models\Skills;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('data/Skills.csv'), 'r');

        fgetcsv($file);

        while(($row = fgetcsv($file)) !== false){
            Skills::create([
                'name'=> $row[0]
            ]);
        }

        fclose($file);
    }
}
