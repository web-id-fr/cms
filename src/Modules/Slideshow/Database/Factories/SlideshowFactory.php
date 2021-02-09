<?php

namespace Webid\Cms\Modules\Slideshow\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Slideshow\Models\Slideshow;

class SlideshowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slideshow::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' =>[
                'fr' => $this->faker->title,
            ],
            'js_controls' => true,
            'js_animate_auto' => true,
        ];
    }
}
