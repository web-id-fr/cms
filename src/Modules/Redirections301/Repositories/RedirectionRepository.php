<?php

namespace Webid\Cms\Modules\Redirections301\Repositories;

use Webid\Cms\Modules\Redirections301\Models\Redirection;

class RedirectionRepository
{
    /** @var Redirection */
    private $model;

    public function __construct(Redirection $model)
    {
        $this->model = $model;
    }

    public function findBySourcePath(string $path): ?Redirection
    {
        return $this->model
            ->where('source_url', 'REGEXP', "/?{$path}/?")
            ->first();
    }
}
