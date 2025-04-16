<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exhibition;

class ExhibitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exhibition::factory()->count(10)->create();
    }
}
