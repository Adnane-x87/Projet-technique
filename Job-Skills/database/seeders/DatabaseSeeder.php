<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,    // First: create users
            SkillsSeeder::class,  // Second: create skills
            EmploiSeeder::class,  // Third: create emplois (depends on users and skills)
        ]);
    }
}

