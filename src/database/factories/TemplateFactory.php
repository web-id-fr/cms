<?php

namespace Webid\Cms\Database\Factories;

use App\Models\Template;
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
                'fr' => $this->faker->words(3, true),
                'en' => $this->faker->words(3, true),
            ],
            'slug' => [
                'fr' => $this->faker->slug,
                'en' => $this->faker->slug,
            ],
            'status' => Template::_STATUS_PUBLISHED,
            'indexation' => rand(0, 1),
            'follow' => rand(0, 1),
            'publish_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
