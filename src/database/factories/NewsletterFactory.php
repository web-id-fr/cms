<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Newsletter\Newsletter;

class NewsletterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Newsletter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'lang' => $this->faker->locale,
        ];
    }
}
