<?php

namespace Webid\Cms\Modules\Articles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Articles\Models\ArticleTag;

class ArticleTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => [
                'fr' => $this->faker->word,
            ],
        ];
    }
}
