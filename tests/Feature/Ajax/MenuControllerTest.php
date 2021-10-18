<?php

namespace Webid\Cms\Tests\Feature\Ajax;

use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Models\Menu\MenuItem;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\TestCase;

class MenuControllerTest extends TestCase
{
    use TemplateCreator;

    const MENUS_INDEX_ROUTE = 'ajax.menus.index';

    /** @test */
    public function test_we_can_get_menus()
    {
        // On crée le menu
        $menu = Menu::factory()->create(['title' => ['fr' => 'mon super menu']]);

        // On crée un item + sous-menu
        $parent_template = Template::factory()->create(['id' => 1]);
        $parent_menu_item = MenuItem::factory()
            ->hasItem($parent_template)
            ->forMenu($menu)
            ->create();

        $child_menu_item = MenuItem::factory()
            ->hasItem(MenuCustomItem::factory()->hasForm()->create(['id' => 2]))
            ->hasParent($parent_template)
            ->forMenu($menu)
            ->create();

        // On attache les items au menu
        $menu->items()->saveMany([
            $parent_menu_item,
            $child_menu_item,
        ]);

        // On attache le menu à une zone "test"
        DB::table('menus_zones')->insert([
            'menu_id' => $menu->getKey(),
            'zone_id' => 'test',
        ]);

        // On teste la route
        $this->beDummyUser();
        $response = $this->ajaxGet(route(self::MENUS_INDEX_ROUTE))
            ->assertSuccessful();

        $this->assertEquals($menu->getKey(), $response->json('0.id'));
        $this->assertEquals($menu->title, $response->json('0.title'));
        $this->assertEquals(['test'], $response->json('0.zones'));
        $this->assertCount(1, $response->json('0.items'));
    }

    /** @test */
    public function test_we_can_get_menus_if_we_delete_parent_page()
    {
        // On crée le menu
        $menu = Menu::factory()->create();

        // On crée les pages
        $page1 = $this->createPublicTemplate([
            'id' => 1,
            'parent_page_id' => null,
            'reference_page_id' => null,
        ]);
        $page2 = $this->createPublicTemplate([
            'id' => 2,
            'parent_page_id' => null,
            'reference_page_id' => null,
        ]);
        $page3 = $this->createPublicTemplate([
            'id' => 3,
            'parent_page_id' => null,
            'reference_page_id' => null,
        ]);
        $page4 = $this->createPublicTemplate([
            'id' => 4,
            'parent_page_id' => 3,
            'reference_page_id' => null,
        ]);
        $page5 = $this->createPublicTemplate([
            'id' => 5,
            'parent_page_id' => 4,
            'reference_page_id' => 1,
        ]);

        // On crée les menu items
        $menuItem1 = MenuItem::factory()
            ->forMenu($menu)
            ->hasItem($page1)
            ->create();

        $menuItem2 = MenuItem::factory()
            ->forMenu($menu)
            ->hasParent($page1)
            ->hasItem($page2)
            ->create();

        $menuItem3 = MenuItem::factory()
            ->forMenu($menu)
            ->hasParent($page2)
            ->hasItem($page3)
            ->create();

        $menuItem5 = MenuItem::factory()
            ->forMenu($menu)
            ->hasParent($page3)
            ->hasItem($page5)
            ->create();

        $menuItem4 = MenuItem::factory()
            ->forMenu($menu)
            ->hasParent($page5)
            ->hasItem($page4)
            ->create();

        // On supprime une page parente
        DB::table($page2->getTable())->whereId($page2->getKey())->delete();

        $menu->items()->saveMany([
            $menuItem1,
            $menuItem2,
            $menuItem3,
            $menuItem4,
            $menuItem5,
        ]);

        // On teste la route
        $this->beDummyUser();
        $response = $this
            ->ajaxGet(route(self::MENUS_INDEX_ROUTE))
            ->assertSuccessful();

        $this->assertEquals($menu->getKey(), $response->json('0.id'));
        $this->assertEquals($menu->title, $response->json('0.title'));
        $this->assertNotEmpty($response->json('0.items'));
    }
}
