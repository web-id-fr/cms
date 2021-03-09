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
                [
                    "key" => "2nRvHkFHn8pxGmYu",
                    "layout" => "image",
                    "attributes" => [
                        "layout" => "image",
                        "image" => "fake.png",
                        "full_image" => "https://www.fake.png",
                        "balise_alt" => [
                            'fr' => $this->faker->text,
                        ],
                    ]
                ],
                [
                    "key" => "tkBsQ7uCQMJCcRV6",
                    "layout" => "video",
                    "attributes" => [
                        "layout" => "video",
                        "video" => "fake.mp4",
                        "full_video" => "https://www.fake.mp4"
                    ]
                ]
            ]),
            'article_type' => array_rand(Article::availableArticleTypes()),
            'quotation' => [
                'fr' => $this->faker->text,
            ],
            'author' => [
                'fr' => $this->faker->name,
            ],
            'not_display_in_list' => false,
            'metatitle' => [],
            'metadescription' => [],
            'opengraph_title' => [],
            'opengraph_description' => [],
            'opengraph_picture' => null,
            'publish_at' => null,
        ];
    }
}
