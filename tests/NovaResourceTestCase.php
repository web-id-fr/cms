<?php

namespace Webid\Cms\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Webid\Cms\Tests\Helpers\Traits\DummyUserCreator;

class NovaResourceTestCase extends TestCase
{
    use DummyUserCreator;

    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createDummyUser();
    }

    /** @return string */
    protected function getResourceName(): string
    {
        // Define your resource name.
    }

    /** @return Model */
    protected function getModel(): Model
    {
        // Define your Model.
    }

    /** @test */
    public function we_can_create_resource()
    {
        $this->actingAs($this->user)
            ->novaCreate($this->getResourceName())
            ->assertOk();
    }

    /** @test */
    public function we_can_show_index()
    {
        $this->actingAs($this->user)
            ->novaIndex($this->getResourceName())
            ->assertOk();
    }

    /** @test */
    public function we_can_show_detail()
    {
        $this->actingAs($this->user)
            ->novaDetail($this->getResourceName(), $this->getModel()->getKey())
            ->assertOk();
    }

    /** @test */
    public function we_can_edit_resource()
    {
        $this->actingAs($this->user)
            ->novaEdit($this->getResourceName(), $this->getModel()->getKey())
            ->assertOk();
    }
}
