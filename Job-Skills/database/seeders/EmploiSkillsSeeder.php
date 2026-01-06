<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class EmploiSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('data/EmploiSkills.csv'), 'r');

        fgetcsv($file);

        while(($row = fgetcsv($file)) !== false){
            DB::table('Emploi_skills')->insert([
              'Emploi_id' => $row[0],
              'skills_id' =>$row[1],
              'created_at' => now(),
              'update_at' => now()
            ]);
        }

        fclose($file);
    }
}
