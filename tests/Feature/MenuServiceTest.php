<?php

namespace Webid\Cms\Tests\Feature;

use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Models\Menu\MenuItem;
use Webid\Cms\App\Services\MenuService;
use Webid\Cms\Tests\TestCase;

class MenuServiceTest extends TestCase
{
    /** @test */
    public function test_menus_are_generated_well()
    {
        //////////////
        /// FIXTURES
        //////////////

        // On crée le menu
        $menu = Menu::factory()->create(['title' => ['fr' => 'mon super menu']]);

        // On crée un premier menu + sous-menu
        $parent_template = Template::factory()->create(['id' => 1]);
        $parent_menu_item_1 = MenuItem::factory()
            ->hasItem($parent_template)
            ->forMenu($menu)
            ->create();

        $child_menu_item_on_parent_1 = MenuItem::factory()
            ->hasItem(MenuCustomItem::factory()->hasForm()->create(['id' => 2]))
            ->hasParent($parent_template)
            ->forMenu($menu)
            ->create();

        // On crée un second menu + sous-menu
        $parent_custom_item = MenuCustomItem::factory()->hasForm()->create(['id' => 1]);
        $parent_menu_item_2 = MenuItem::factory()
            ->hasItem($parent_custom_item)
            ->forMenu($menu)
            ->create();

        $child_menu_item_on_parent_2 = MenuItem::factory()
            ->hasItem(Template::factory()->create(['id' => 2]))
            ->hasParent($parent_custom_item)
            ->forMenu($menu)
            ->create();

        // On attache tous les menu items au menu
        $menu->related()->saveMany([
            $parent_menu_item_1,
            $child_menu_item_on_parent_1,
            $parent_menu_item_2,
            $child_menu_item_on_parent_2,
        ]);

        // On attache le menu à une zone "test"
        DB::table('menus_zones')->insert([
            'menu_id' => $menu->getKey(),
            'zone_id' => 'test',
        ]);

        ////////////////
        /// ASSERTIONS
        ////////////////

        $generated_menus = MenuService::make()->getMenus();

        // On a un seul menu en sortie
        $this->assertCount(1, $generated_menus);
        $this->assertEquals('mon super menu', $generated_menus['test']['title']);

        // On a seulement 2 éléments "parents"
        $this->assertCount(2, $generated_menus['test']['zones']);

        // Chaque élément "parent" n'a qu'un seul élément "enfant"
        $this->assertCount(1, $generated_menus['test']['zones'][0]['children']);
        $this->assertCount(1, $generated_menus['test']['zones'][1]['children']);
    }
}
