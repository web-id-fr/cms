<?php

namespace Webid\Cms\App\Repositories;

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
    public function getPublishedTemplates()
    {
        $models = $this->model->all()
            ->where('status', Template::_STATUS_PUBLISHED)
            ->load('menus');

        $models->each(function ($model) {
            $model->chargeComponents();
        });

        return $models;
    }

    /**
     * @return Collection
     */
    public function getSlugForHomepage()
    {
        $model = $this->model->select('slug')
            ->where('homepage', true)
            ->first();

        $model->chargeComponents();

        return $model;
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

    /**
     * @param string $slug
     * @param string $language
     *
     * @return mixed
     */
    public function getLastCorrespondingSlugWithNumber(string $slug, string $language)
    {
        $slug = strtolower($slug);
        return $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug(-[1-9])\"")
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getPublishedAndIndexedTemplates(): Collection
    {
        return $this->model
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->orWhere('publish_at', '<', now())
                    ->orWhereNull('publish_at');
            })
            ->where('indexation', true)
            ->get();
    }
}
