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
            'content' => json_encode([
                ["key" => "2nRvHkFHn8pxGmYu", "layout" => "image", "attributes" => ["image" => "fake.png"]],
                ["key" => "tkBsQ7uCQMJCcRV6", "layout" => "video", "attributes" => ["video" => "fake.mp4"]]
            ]),
            'metatitle' => [],
            'metadescription' => [],
            'opengraph_title' => [],
            'opengraph_description' => [],
            'opengraph_picture' => null,
            'publish_at' => null,
        ];
    }
}
