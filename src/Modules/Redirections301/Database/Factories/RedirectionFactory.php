<?php

namespace Webid\Cms\Modules\Redirections301\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Redirections301\Models\Redirection;

class RedirectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Redirection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'source_url' => $this->faker->url,
            'destination_url' => $this->faker->url,
        ];
    }
}
