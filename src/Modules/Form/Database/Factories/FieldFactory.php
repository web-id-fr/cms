<?php

namespace Webid\Cms\Modules\Form\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Form\Models\Field;

class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $field_type = array_rand(config('fields_type'));
        $field_name = "Champ " . config('fields_type')[$field_type] . " : " . $this->faker->unique()->words(3, true);

        return [
            "field_name" => $field_name,
            "field_type" => $field_type,
            "placeholder" => [
                'fr' => $field_name,
            ],
            "required" => rand(0, 1),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
