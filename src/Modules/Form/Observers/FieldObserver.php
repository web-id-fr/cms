<?php

namespace Webid\Cms\Modules\Form\Observers;

use Illuminate\Support\Str;
use Webid\Cms\Modules\Form\Models\Field;

class FieldObserver
{
    /**
     * @param Field $field
     */
    public function saving(Field $field): void
    {
        $field->field_name = Str::slug($field->field_name);
    }
}
