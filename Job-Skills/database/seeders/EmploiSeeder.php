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

        $index = 1;
        while(($row = fgetcsv($file)) !== false){
            // Use placeholder images from picsum.photos
            $imageUrl = 'https://picsum.photos/seed/' . urlencode($row[2]) . '/400/300';
            
            Emploi::create([
                'title'=> $row[0],
                'description'=> $row[1],
                'company'=>$row[2],
                'image'=> $imageUrl,
                'user_id'=>$row[4]
            ]);
            $index++;
        }

        fclose($file);
    }
}
