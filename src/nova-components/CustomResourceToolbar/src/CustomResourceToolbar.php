<?php

namespace Webid\CustomResourceToolbar;

use Laravel\Nova\ResourceTool;

class CustomResourceToolbar extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Custom Resource Toolbar';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'custom-create-header';
    }
}
