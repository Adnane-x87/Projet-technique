<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emploi;
use App\Models\Skills;

class EmploiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (($handle = fopen(database_path('data/Emploi.csv'), 'r')) !== false) {
            $header = fgetcsv($handle); 
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                
                $emploi = Emploi::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'company' => $data['company'],
                    'image' => $data['image'],
                    'user_id' => $data['user_id'],
                ]);

                $skill = Skills::where('name', trim($data['skills']))->first();
                if ($skill) {
                    $emploi->skills()->attach($skill->id);
                }
            }
            fclose($handle);
        }
    }
}
