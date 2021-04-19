<?php

namespace Webid\Cms\Modules\Redirections301\Repositories;

use Webid\Cms\Modules\Redirections301\Models\Redirection;

class RedirectionRepository
{
    private Redirection $model;

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

    public function create(string $from, string $to): Redirection
    {
        return $this->model->create([
            'source_url' => $from,
            'destination_url' => $to,
        ]);
    }
}
