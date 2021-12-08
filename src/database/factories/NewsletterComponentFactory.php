<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Components\NewsletterComponent;

class NewsletterComponentFactory extends Factory
{
    protected $model = NewsletterComponent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'status' => NewsletterComponent::_STATUS_PUBLISHED,
            'title' => $this->faker->word,
            'cta_name' => $this->faker->word,
            'placeholder' => $this->faker->words(3, true),
        ];
    }
}
