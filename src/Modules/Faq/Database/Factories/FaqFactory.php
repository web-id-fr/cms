<?php

namespace Webid\Cms\Modules\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\Faq\Models\Faq;
use Webid\Cms\Modules\Faq\Models\FaqTheme;

class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'question' => [
                'fr' => $this->faker->text,
            ],
            'answer' => [
                'fr' => $this->faker->text,
            ],
            'order' => rand(1, 5),
            'status' => Faq::_STATUS_PUBLISHED,
            'faq_theme_id' => function () {
                return FaqTheme::factory()->create()->getKey();
            },
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
