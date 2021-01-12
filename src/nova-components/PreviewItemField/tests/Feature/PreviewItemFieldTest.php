<?php

namespace Webid\PreviewItemField\Tests\Feature;

use Webid\PreviewItemField\Tests\PreviewItemFieldTestCase;

class PreviewItemFieldTest extends PreviewItemFieldTestCase
{
    /** @test */
    public function we_can_store_template_data()
    {
        $this->beDummyUser();

        $data = [
            "homepage" => true,
            "title" => "titre-en-fr",
            "slug" => "slug-en-fr",
            "lang" => "fr",
        ];

        $response = $this->ajaxPost(route('store.preview'), $data)->assertSuccessful();
        $response->assertExactJson(['token' => $response['token']]);
        $response->assertSessionHas($response['token'], $data);
    }
}
