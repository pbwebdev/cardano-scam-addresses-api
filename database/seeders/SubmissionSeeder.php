<?php

namespace Database\Seeders;

use App\Models\Submission;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Submission::factory()->count(10)->create();
    }
}
