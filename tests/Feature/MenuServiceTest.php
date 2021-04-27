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
    public function setUp(): void
    {
        parent::setUp();

        DB::enableQueryLog();
    }

    /** @test */
    public function test_menus_are_generated_well()
    {
        //////////////
        /// FIXTURES
        //////////////

        // On crée le menu
        $menu = Menu::factory()->create(['title' => ['fr' => 'mon super menu']]);

        // On crée un premier item + sous-menu
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

        // On crée un second item + sous-menu
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

        // On attache tous les items au menu
        $menu->items()->saveMany([
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

        $this->assertMaxSqlQueries(29);
    }

    /** @test */
    public function test_menu_items_children_are_not_generated_in_wrong_menus()
    {
        //////////////
        /// FIXTURES
        //////////////

        /// ON CREE UN PREMIER MENU

        // On crée le premier menu
        $menu_1 = Menu::factory()->create(['title' => ['fr' => 'menu 1']]);

        // On crée un élément & un sous-élément
        $parent_template = Template::factory()->create(['id' => 1]);
        $parent_menu_item_1 = MenuItem::factory()
            ->hasItem($parent_template)
            ->forMenu($menu_1)
            ->create();

        $child_menu_item_on_parent_1 = MenuItem::factory()
            ->hasItem(MenuCustomItem::factory()->create(['id' => 1]))
            ->hasParent($parent_template)
            ->forMenu($menu_1)
            ->create();

        // On attache tous les items au menu
        $menu_1->items()->saveMany([
            $parent_menu_item_1,
            $child_menu_item_on_parent_1,
        ]);

        /// ON CREE UN DEUXIEME MENU

        // On crée le deuxième menu
        $menu_2 = Menu::factory()->create(['title' => ['fr' => 'menu 2']]);

        // On crée un élément & un sous-élément (on lui attache la même page que dans le menu 1)
        $parent_menu_item_2 = MenuItem::factory()
            ->hasItem($parent_template)
            ->forMenu($menu_2)
            ->create();

        $child_menu_item_on_parent_2 = MenuItem::factory()
            ->hasItem(MenuCustomItem::factory()->create(['id' => 2]))
            ->hasParent($parent_template)
            ->forMenu($menu_2)
            ->create();

        // On attache tous les items au menu
        $menu_2->items()->saveMany([
            $parent_menu_item_2,
            $child_menu_item_on_parent_2,
        ]);

        // On attache les menus à des zones "test"
        DB::table('menus_zones')->insert([
            [
                'menu_id' => $menu_1->getKey(),
                'zone_id' => 'test 1',
            ],
            [
                'menu_id' => $menu_2->getKey(),
                'zone_id' => 'test 2',
            ],
        ]);

        ////////////////
        /// ASSERTIONS
        ////////////////

        $generated_menus = MenuService::make()->getMenus();

        // On a bien nos 2 menus
        $this->assertCount(2, $generated_menus);
        $this->assertEquals('menu 1', $generated_menus['test 1']['title']);
        $this->assertEquals('menu 2', $generated_menus['test 2']['title']);

        // Chaque menu n'a bien qu'un seul élément parent
        $this->assertCount(1, $generated_menus['test 1']['zones']);
        $this->assertCount(1, $generated_menus['test 2']['zones']);

        // Les éléments du menu ne contiennent qu'un sous-élément
        $this->assertCount(1, $generated_menus['test 1']['zones'][0]['children']);
        $this->assertEquals(1, $generated_menus['test 1']['zones'][0]['children'][0]['id']);

        $this->assertCount(1, $generated_menus['test 2']['zones'][0]['children']);
        $this->assertEquals(2, $generated_menus['test 2']['zones'][0]['children'][0]['id']);

        $this->assertMaxSqlQueries(16);
    }

    private function assertMaxSqlQueries(int $max): void
    {
        $count = count(DB::getQueryLog());

        $this->assertLessThanOrEqual(
            $max,
            $count,
            "Le nombre de requêtes SQL a augmenté ! Il est de {$count} alors qu'il devrait être de {$max} au maximum."
        );
    }
}
