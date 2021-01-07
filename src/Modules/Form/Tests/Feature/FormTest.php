<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Webid\Cms\Modules\Form\Mail\SendForm;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\FormCreator;

class FormTest extends FormTestCase
{
    use FormCreator;

    const _FORM_ROUTE = 'send.form';

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'forms';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createForm([
            'recipient_type' => 2
        ]);
    }

    /** @test */
    public function we_can_send_contact_form_with_required_information()
    {
        Mail::fake();

        $this->ajaxPost(route(self::_FORM_ROUTE, [
            'lang' => 'fr'
        ]))->assertSuccessful();

        Mail::assertSent(SendForm::class);
    }
}
