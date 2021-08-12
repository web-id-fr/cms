<?php

namespace Webid\Cms\Modules\JavaScript\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\Modules\JavaScript\Models\CodeSnippet;

class CodeSnippetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CodeSnippet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'source_code' => sprintf(
                'console.log("%s")',
                $this->faker->sentence(),
            ),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
