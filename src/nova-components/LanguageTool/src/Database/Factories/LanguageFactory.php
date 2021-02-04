<?php

namespace Webid\LanguageTool\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\LanguageTool\Models\Language;

class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Français',
            'flag' => 'fr',
        ];
    }
}
