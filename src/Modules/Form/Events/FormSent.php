<?php

namespace Webid\Cms\Modules\Form\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $fields;
    public array $files;

    public function __construct(array $fields, array $files = null)
    {
        $this->fields = $fields;
        $this->files = $files ?? [];
    }
}
