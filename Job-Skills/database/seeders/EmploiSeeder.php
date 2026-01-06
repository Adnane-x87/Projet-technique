<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Emploi;
class EmploiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('data/Emploi.csv'), 'r');

        fgetcsv($file);

        while(($row = fgetcsv($file)) !== false){

            Emploi::create([
                'title'=> $row[0],
                'description'=> $row[1],
                'company'=>$row[2],
                'image'=> $row[3],
                'user_id'=>$row[4]
            ]);
        }

        fclose($file);
    }
}
