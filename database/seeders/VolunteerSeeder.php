<?php

namespace Database\Seeders;

use App\Models\Volunteer;
use Database\Factories\VolunteerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Volunteer::factory()->count(5)->create();
    }
}
