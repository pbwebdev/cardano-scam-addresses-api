<?php

namespace Database\Seeders;

use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Website::factory()->count(10)->create();
    }
}
