<?php

namespace Webid\Cms\Database\Factories;

use Webid\Cms\App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => [
                'fr' => $this->faker->title,
                'en' => $this->faker->title,
            ],
            'slug' => [
                'fr' => $this->faker->unique()->slug,
                'en' => $this->faker->unique()->slug,
            ],
            'status' => Template::_STATUS_PUBLISHED,
            'indexation' => rand(0, 1),
            'follow' => rand(0, 1),
            'publish_at' => $this->faker->date('Y-m-d H:i:s'),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
