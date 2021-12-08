<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Components\GalleryComponent;

class GalleryComponentFactory extends Factory
{
    protected $model = GalleryComponent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'status' => GalleryComponent::_STATUS_PUBLISHED,
        ];
    }
}
