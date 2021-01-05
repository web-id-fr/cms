<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Dummy\DummyUser;

class DummyUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DummyUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('passwd'),
        ];
    }
}
