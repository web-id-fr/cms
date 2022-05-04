<?php

namespace Webid\Cms\App\Repositories\Popin;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Popin\Popin;

class PopinRepository
{
    public function __construct(private Popin $model)
    {
    }

    public function getPublishedPopins(): Collection
    {
        return $this->model->all()
            ->where('status', Popin::_STATUS_PUBLISHED);
    }

    public function findByPageId(int $id): Collection
    {
        return $this->model
            ->whereHas('templates', function ($query) use ($id) {
                $query->where('template_id', '=', $id);
            })
            ->where('status', '=', Popin::_STATUS_PUBLISHED)
            ->get();
    }
}
