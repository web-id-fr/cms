<?php

namespace Webid\Cms\Src\App\Repositories;

use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TemplateRepository extends BaseRepository
{
    /**
     * TemplateRepository constructor.
     *
     * @param Template $model
     */
    public function __construct(Template $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all()
    {
        return $this->model->all()
            ->where('status', Template::_STATUS_PUBLISHED);
    }

    /**
     * @return Collection
     */
    public function getSlugForHomepage()
    {
        return $this->model->select('slug')
            ->where('homepage', true)
            ->first();
    }

    /**
     * @param string $slug
     * @param string $language
     *
     * @return Template
     */
    public function getBySlug(string $slug, string $language): Template
    {
        $slug = strtolower($slug);

        $model = $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug\"")
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->whereNull('publish_at')->orWhere('publish_at', '<=', Carbon::now());
            })
            ->firstOrFail();

        $model->chargeComponents();

        return $model;
    }
}
