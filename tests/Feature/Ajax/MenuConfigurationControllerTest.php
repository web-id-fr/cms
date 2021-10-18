<?php

namespace Webid\Cms\Tests\Feature\Ajax;

use Illuminate\Support\Facades\DB;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Services\MenuService;
use Webid\Cms\Tests\TestCase;

class MenuConfigurationControllerTest extends TestCase
{
    const INDEX_ROUTE = 'ajax.menu_configuration.index';
    const UPDATE_ZONE_ROUTE = 'ajax.menu_configuration.update';

    public function setUp(): void
    {
        parent::setUp();

        $this->beDummyUser();

        $menuService = $this->app->makeWith(MenuService::class, [
            'templatesPath' => package_base_path('/tests/Resources/views'),
        ]);
        $this->app->instance(MenuService::class, $menuService);
    }

    /** @test */
    public function test_we_can_get_menus_configuration()
    {
        $response = $this->ajaxGet(route(self::INDEX_ROUTE))
            ->assertSuccessful();

        $response->assertJsonCount(2);

        $this->assertEquals('zone-1', $response->json('0.menuID'));
        $this->assertEquals('zone-2', $response->json('1.menuID'));
    }

    /** @test */
    public function test_we_can_attach_a_menu_to_a_menu_zone()
    {
        $menu = Menu::factory()->create();

        $this->assertDatabaseMissing('menus_zones', [
            'menu_id' => $menu->getKey(),
            'zone_id' => 'zone_test',
        ]);

        $this
            ->ajaxPost(
                route(self::UPDATE_ZONE_ROUTE),
                [
                    'menu_id' => $menu->getKey(),
                    'zone_id' => 'zone_test',
                ]
            )
            ->assertSuccessful()
            ->assertJson([
                'success' => true,
                'message' => "The menu #{$menu->getKey()} had been successfully assigned to zone zone_test.",
            ]);

        $this->assertDatabaseHas('menus_zones', [
            'menu_id' => $menu->getKey(),
            'zone_id' => 'zone_test',
        ]);
    }

    /** @test */
    public function test_we_can_detach_a_menu_from_a_menu_zone()
    {
        $menu = Menu::factory()->create();
        DB::table('menus_zones')->insert([
            'menu_id' => $menu->getKey(),
            'zone_id' => 'zone_test',
        ]);

        $this->assertDatabaseHas('menus_zones', [
            'menu_id' => $menu->getKey(),
            'zone_id' => 'zone_test',
        ]);

        $this
            ->ajaxPost(
                route(self::UPDATE_ZONE_ROUTE),
                [
                    'zone_id' => 'zone_test',
                ]
            )
            ->assertSuccessful()
            ->assertJson([
                'success' => true,
                'message' => "The menus attached to zone zone_test had been successfully removed.",
            ]);

        $this->assertDatabaseMissing('menus_zones', [
            'menu_id' => $menu->getKey(),
            'zone_id' => 'zone_test',
        ]);
    }

    /** @test */
    public function test_we_cannot_update_configuration_with_invalid_parameters()
    {
        $wrongParametersExamples = [
            [
                'zone_id' => '',
            ],
            [
                'zone_id' => 'test',
                'menu_id' => '',
            ],
        ];

        foreach ($wrongParametersExamples as $wrongParams) {
            $this
                ->ajaxPost(
                    route(self::UPDATE_ZONE_ROUTE),
                    $wrongParams
                )
                ->assertStatus(400)
                ->assertJson([
                    'success' => false,
                ]);
        }
    }
}
