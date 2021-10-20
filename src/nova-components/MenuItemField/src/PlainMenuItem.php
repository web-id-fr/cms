<?php

namespace Webid\MenuItemField;

class PlainMenuItem
{
    public int $menuableId;
    public string $menuableType;
    public int $order;
    public ?int $parentId;
    public ?string $parentType;
    /** @var array<PlainMenuItem> */
    public array $children;

    public function __construct(
        int     $menuableId,
        string  $menuableType,
        int     $order,
        ?int    $parentId = null,
        ?string $parentType = null,
        array   $children = []
    ) {
        $this->menuableId = $menuableId;
        $this->menuableType = $menuableType;
        $this->order = $order;
        $this->parentId = $parentId;
        $this->parentType = $parentType;
        $this->children = $children;
    }
}
