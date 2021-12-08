<?php

namespace Webid\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Modules\Form\Models\Form;

class MenuCustomItemFactory extends Factory
{
    protected $model = MenuCustomItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => [
                'fr' => $this->faker->sentence(3),
            ],
            'url' => [
                'fr' => $this->faker->url,
            ],
            'menu_description' => [
                'fr' => $this->faker->sentence,
            ],
            'type_link' => MenuCustomItem::_LINK_URL,
        ];
    }

    public function hasForm(): self
    {
        return $this->state(function () {
            return [
                'type_link' => MenuCustomItem::_LINK_FORM,
                'form_id' => Form::factory(),
            ];
        });
    }
}

