<?php

namespace Webid\Cms\Modules\Form\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Form\Models\Form;

class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->title,
            "title" => [
                'fr' => $this->faker->title,
            ],
            "description" => [
                'fr' => $this->faker->paragraph,
            ],
            "cta_name" => [
                'fr' => $this->faker->paragraph,
            ],
            'status' => Form::_STATUS_PUBLISHED,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
