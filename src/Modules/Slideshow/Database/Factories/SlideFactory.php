<?php

namespace Webid\Cms\Modules\Slideshow\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Slideshow\Models\Slide;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' =>  [
                'fr' => $this->faker->title,
            ],
            'description' =>  [
                'fr' => $this->faker->paragraph,
            ],
            'cta_name' =>  [
                'fr' => $this->faker->name,
            ],
            'cta_url' =>  [
                'fr' => $this->faker->url,
            ],
            'url' =>  [
                'fr' => $this->faker->url,
            ],
            'image' => "fake.png",
        ];
    }
}
