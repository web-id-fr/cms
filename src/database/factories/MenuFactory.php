<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Menu\Menu;

class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
        ];
    }
}

