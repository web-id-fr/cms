<?php

namespace Webid\Cms\Modules\Articles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Articles\Models\Article;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

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
            ],
            'slug' => [
                'fr' => $this->faker->slug,
            ],
            'article_image' => 'image.png',
            'status' => Article::_STATUS_PUBLISHED,
            'extrait' => [
                'fr' => $this->faker->text,
            ],
            'content' => [
                'fr' => $this->faker->text,
            ],
            'metatitle' => [],
            'metadescription' => [],
            'opengraph_title' => [],
            'opengraph_description' => [],
            'opengraph_picture' => null,
            'publish_at' => null,
        ];
    }
}
