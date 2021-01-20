<?php

namespace Webid\Cms\Modules\Form\Tests\Helpers;

use Webid\Cms\Modules\Form\Models\Recipient;

trait RecipientCreator
{
    /**
     * @param array $parameters
     *
     * @return Recipient
     */
    private function createRecipient(array $parameters = []): Recipient
    {
        return Recipient::factory($parameters)->create();
    }
}
