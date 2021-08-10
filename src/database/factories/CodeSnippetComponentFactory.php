<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Components\CodeSnippetComponent;
use Webid\Cms\Modules\JavaScript\Models\CodeSnippet;

class CodeSnippetComponentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CodeSnippetComponent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'status' => CodeSnippetComponent::_STATUS_PUBLISHED,
            'code_snippet_id' => function () {
                return CodeSnippet::factory()->create()->getKey();
            },
        ];
    }
}
