<?php

namespace Webid\MenuItemField\Tests\Feature;

use Webid\Cms\App\Models\Menu\Menu;
use Webid\MenuItemField\Tests\MenuItemFieldTestCase;

class MenuTest extends MenuItemFieldTestCase
{
    const UPDATE_ROUTE = '/nova-api/menus/{{id}}?viaResource=&viaResourceId=&viaRelationship=&editing=true&editMode=update';

    public function setUp(): void
    {
        parent::setUp();

        $this->beDummyUser();
    }

    /** @test */
    public function test_we_can_save_a_menu_with_parent_and_children_items()
    {
        $menu = Menu::factory()->create(['id' => 7]);

        $first_parent_item = [
            'id' => 1,
            'type' => "App\Models\Template",
        ];
        $first_child_item = [
            'id' => 3,
            'type' => "Webid\Cms\App\Models\Menu\MenuCustomItem",
        ];

        $second_parent_item = [
            'id' => 1,
            'type' => "Webid\Cms\App\Models\Menu\MenuCustomItem",
        ];
        $second_child_item = [
            'id' => 4,
            'type' => "Webid\Cms\App\Models\Menu\MenuCustomItem",
        ];

        $data = [
            'title' => 'lorem ipsum',
            'menu_item' => json_encode([
                [
                    "id" => $first_parent_item['id'],
                    "menuable_type" => $first_parent_item['type'],
                    "children" => [
                        [
                            "id" => $first_child_item['id'],
                            "title" => [
                                "fr" => "Blog",
                            ],
                            "url" => [
                                "fr" => "blog",
                            ],
                            "target" => "_SELF",
                            "created_at" => "2021-08-24T08:55:46.000000Z",
                            "updated_at" => "2021-08-24T08:57:15.000000Z",
                            "type_link" => 1,
                            "form_id" => null,
                            "menu_description" => [
                                "fr" => "Blog",
                            ],
                            "children" => [],
                            "menuable_type" => $first_child_item['type'],
                        ],
                    ],
                ],
                [
                    "id" => $second_parent_item['id'],
                    "menuable_type" => $second_parent_item['type'],
                    "children" => [
                        [
                            "id" => $second_child_item['id'],
                            "title" => [
                                "fr" => "Lien vers formulaire",
                            ],
                            "url" => null,
                            "target" => "_self",
                            "created_at" => "2021-08-11T10:03:31.000000Z",
                            "updated_at" => "2021-08-11T10:03:31.000000Z",
                            "type_link" => 2,
                            "form_id" => null,
                            "menu_description" => [
                                "fr" => "lien vers formulaire",
                            ],
                            "children" => [],
                            "menuable_type" => $second_child_item['type'],
                        ],
                    ],
                ],
            ]),
        ];

        $this->ajaxPut(
            str_replace('{{id}}', $menu->getKey(), self::UPDATE_ROUTE),
            $data
        )->assertSuccessful();

        $this->assertDatabaseCount('menuables', 4);

        // First item & sub-item
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => $first_parent_item['type'],
            'menuable_id' => $first_parent_item['id'],
            'parent_type' => null,
            'parent_id' => null,
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => $first_child_item['type'],
            'menuable_id' => $first_child_item['id'],
            'parent_type' => $first_parent_item['type'],
            'parent_id' => $first_parent_item['id'],
        ]);

        // Second item & sub-item
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => $second_parent_item['type'],
            'menuable_id' => $second_parent_item['id'],
            'parent_type' => null,
            'parent_id' => null,
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_type' => $second_child_item['type'],
            'menuable_id' => $second_child_item['id'],
            'parent_type' => $second_parent_item['type'],
            'parent_id' => $second_parent_item['id'],
        ]);
    }

    public function test_we_can_save_a_menu_with_many_items_with_deep_children()
    {
        $menu = Menu::factory()->create(['id' => 7]);

        $data = [
            'title' => 'menu',
            'menu_item' => json_encode([
                [
                    'id' => 1,
                    'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
                    'children' => [
                        [
                            'id' => 2,
                            'title' => [
                                'fr' => 'Lien vers formulaire',
                            ],
                            'url' => null,
                            'target' => '_self',
                            'created_at' => '2021-08-11T10:03:31.000000Z',
                            'updated_at' => '2021-08-11T10:03:31.000000Z',
                            'type_link' => 2,
                            'form_id' => null,
                            'menu_description' => [
                                'fr' => 'lien vers formulaire',
                            ],
                            'children' => [
                                [
                                    'id' => 1,
                                    'title' => [
                                        'fr' => 'Page d\'accueil',
                                    ],
                                    'slug' => [
                                        'fr' => 'index',
                                    ],
                                    'indexation' => 1,
                                    'status' => 0,
                                    'homepage' => 1,
                                    'contains_articles_list' => 0,
                                    'metatitle' => null,
                                    'metadescription' => null,
                                    'opengraph_title' => null,
                                    'opengraph_description' => null,
                                    'opengraph_picture' => null,
                                    'publish_at' => null,
                                    'created_at' => '2021-06-29T15:23:53.000000Z',
                                    'updated_at' => '2021-09-01T09:07:26.000000Z',
                                    'follow' => 1,
                                    'opengraph_picture_alt' => null,
                                    'menu_description' => null,
                                    'meta_keywords' => null,
                                    'parent_page_id' => null,
                                    'reference_page_id' => null,
                                    'children' => [
                                        [
                                            'id' => 25,
                                            'title' => [
                                                'fr' => 'Article',
                                                'en' => 'Article in english',
                                            ],
                                            'slug' => [
                                                'fr' => 'article',
                                                'en' => 'article-in-english',
                                            ],
                                            'indexation' => 1,
                                            'status' => 0,
                                            'homepage' => 0,
                                            'contains_articles_list' => 0,
                                            'metatitle' => null,
                                            'metadescription' => [
                                                'en' => null,
                                            ],
                                            'opengraph_title' => null,
                                            'opengraph_description' => [
                                                'en' => null,
                                            ],
                                            'opengraph_picture' => null,
                                            'publish_at' => null,
                                            'created_at' => '2021-08-18T08:41:14.000000Z',
                                            'updated_at' => '2021-09-23T07:15:40.000000Z',
                                            'follow' => 1,
                                            'opengraph_picture_alt' => null,
                                            'menu_description' => [
                                                'en' => 'Article in english',
                                            ],
                                            'meta_keywords' => null,
                                            'parent_page_id' => 1,
                                            'reference_page_id' => null,
                                            'children' => [],
                                            'menuable_type' => 'App\\Models\\Template',
                                            'isSelected' => true,
                                        ],
                                    ],
                                    'menuable_type' => 'App\\Models\\Template',
                                    'isSelected' => true,
                                ],
                            ],
                            'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
                            'pivot' => [
                                'menu_id' => 7,
                                'menuable_id' => 2,
                                'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
                                'order' => 2,
                                'parent_id' => null,
                                'parent_type' => null,
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 3,
                    'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
                    'children' => [
                        [
                            'id' => 12,
                            'title' => [
                                'fr' => 'page test composants 2',
                                'en' => 'page test composants 2 EN',
                            ],
                            'slug' => [
                                'fr' => 'page-test-composants-2',
                                'en' => 'page-test-composants-2-en',
                            ],
                            'indexation' => 0,
                            'status' => 0,
                            'homepage' => 0,
                            'contains_articles_list' => 0,
                            'metatitle' => null,
                            'metadescription' => [
                                'fr' => null,
                            ],
                            'opengraph_title' => null,
                            'opengraph_description' => [
                                'fr' => null,
                            ],
                            'opengraph_picture' => null,
                            'publish_at' => null,
                            'created_at' => '2021-08-02T11:41:28.000000Z',
                            'updated_at' => '2021-08-18T14:57:02.000000Z',
                            'follow' => 0,
                            'opengraph_picture_alt' => null,
                            'menu_description' => [
                                'fr' => 'page test composants 2',
                                'en' => 'page test composants 2 EN',
                            ],
                            'meta_keywords' => null,
                            'parent_page_id' => 11,
                            'reference_page_id' => null,
                            'children' => [
                                [
                                    'id' => 5,
                                    'title' => [
                                        'fr' => 'Page test ets_scolaire',
                                    ],
                                    'slug' => [
                                        'fr' => 'page-test-ets-scolaire',
                                    ],
                                    'indexation' => 1,
                                    'status' => 0,
                                    'homepage' => 0,
                                    'contains_articles_list' => 0,
                                    'metatitle' => null,
                                    'metadescription' => null,
                                    'opengraph_title' => null,
                                    'opengraph_description' => null,
                                    'opengraph_picture' => null,
                                    'publish_at' => null,
                                    'created_at' => '2021-07-26T13:17:42.000000Z',
                                    'updated_at' => '2021-07-26T13:32:04.000000Z',
                                    'follow' => 1,
                                    'opengraph_picture_alt' => null,
                                    'menu_description' => null,
                                    'meta_keywords' => null,
                                    'parent_page_id' => null,
                                    'reference_page_id' => null,
                                    'children' => [],
                                    'menuable_type' => 'App\\Models\\Template',
                                    'isSelected' => true,
                                ],
                            ],
                            'menuable_type' => 'App\\Models\\Template',
                            'isSelected' => true,
                        ],
                    ],
                ],
            ]),
        ];

        $this->ajaxPut(
            str_replace('{{id}}', $menu->getKey(), self::UPDATE_ROUTE),
            $data
        )->assertSuccessful();

        $this->assertDatabaseCount('menuables', 7);

        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 1,
            'menuable_type' => 'App\\Models\\Template',
            'parent_id' => 2,
            'parent_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 1,
            'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
            'parent_id' => null,
            'parent_type' => null,
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 2,
            'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
            'parent_id' => 1,
            'parent_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 3,
            'menuable_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
            'parent_id' => null,
            'parent_type' => null,
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 5,
            'menuable_type' => 'App\\Models\\Template',
            'parent_id' => 12,
            'parent_type' => 'App\\Models\\Template',
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 12,
            'menuable_type' => 'App\\Models\\Template',
            'parent_id' => 3,
            'parent_type' => 'Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem',
        ]);
        $this->assertDatabaseHas('menuables', [
            'menu_id' => $menu->getKey(),
            'menuable_id' => 25,
            'menuable_type' => 'App\\Models\\Template',
            'parent_id' => 1,
            'parent_type' => 'App\\Models\\Template',
        ]);
    }
}
