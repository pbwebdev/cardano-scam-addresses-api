<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\Wallet;
use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var HasFactory $submittableType */
        $submittableType = $this->faker->randomElement([
            Wallet::class,
            Website::class,
        ]);
        $submittable = $submittableType::factory()->create();

        return [
            'content'          => $this->faker->paragraph(),
            'submittable_id'   => $submittable->getKey(),
            'submittable_type' => $submittable->getMorphClass(),
        ];
    }
}
