<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use Webid\Cms\App\Models\Dummy\DummyUser;

trait DummyUserCreator
{
    private function createDummyUser($params = []): DummyUser
    {
        return DummyUser::factory($params)->create();
    }
}
