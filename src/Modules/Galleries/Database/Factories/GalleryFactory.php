<?php

namespace Webid\Cms\Modules\Galleries\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Galleries\Models\Gallery;

class GalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gallery::class;

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
            'folder' => $this->faker->name,
            "cta_name" => [
                'fr' => $this->faker->paragraph,
            ],
            'status' => Gallery::_STATUS_PUBLISHED,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
