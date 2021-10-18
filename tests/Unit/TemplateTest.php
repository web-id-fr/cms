<?php

namespace Webid\Cms\Tests\Unit;

use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Models\Menu\MenuItem;
use Webid\Cms\Tests\Helpers\Traits\TemplateCreator;
use Webid\Cms\Tests\TestCase;

class TemplateTest extends TestCase
{
    use TemplateCreator;

    /** @test */
    public function foreign_menuables_are_deleted_on_template_deletion()
    {
        // Create a menu
        $menu = Menu::factory()->create(['title' => ['fr' => 'mon super menu']]);

        // Create a page
        $template = $this->createPublicTemplate(['id' => 1]);
        $template_menu_item = MenuItem::factory()
            ->hasItem($template)
            ->forMenu($menu)
            ->create();

        // Create children element for this page
        $child_template = $this->createPublicTemplate();
        $child_template_menu_item = MenuItem::factory()
            ->hasItem($child_template)
            ->hasParent($template)
            ->forMenu($menu)
            ->create();

        // Create a custom item
        $custom_item = MenuCustomItem::factory()->create(['id' => 1]);
        $custom_menu_item = MenuItem::factory()
            ->hasItem($custom_item)
            ->forMenu($menu)
            ->create();

        // Attach page & custom item to menu
        $menu->items()->saveMany([
            $template_menu_item,
            $child_template_menu_item,
            $custom_menu_item,
        ]);

        // Check we have everything
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($template),
            'menuable_id' => $template->getKey(),
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($child_template),
            'menuable_id' => $child_template->getKey(),
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($custom_item),
            'menuable_id' => $custom_item->getKey(),
        ]);

        // Delete the template
        $template->delete();

        // Check all menuables related to / children of deleted template are deleted
        $this->assertDatabaseMissing('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($template),
            'menuable_id' => $template->getKey(),
        ]);
        $this->assertDatabaseMissing('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($child_template),
            'menuable_id' => $child_template->getKey(),
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => get_class($custom_item),
            'menuable_id' => $custom_item->getKey(),
        ]);
    }
}
