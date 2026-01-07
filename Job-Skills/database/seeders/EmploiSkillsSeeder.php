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
        $emplois = \App\Models\Emploi::all();
        $skills = \App\Models\Skills::all();

        if ($emplois->isEmpty() || $skills->isEmpty()) {
            return;
        }

        foreach ($emplois as $emploi) {
            // Attach 1 to 3 random skills to each job
            $randomSkills = $skills->random(rand(1, min(3, $skills->count())));
            $emploi->skills()->sync($randomSkills);
        }
    }
}
