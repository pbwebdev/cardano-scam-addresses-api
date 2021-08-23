<?php

namespace Database\Factories;

use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebsiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Website::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->domainName(),
        ];
    }
}
