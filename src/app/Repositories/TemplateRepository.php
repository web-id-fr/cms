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
        return $this->model
            ->where('status', Template::_STATUS_PUBLISHED)
            ->with('related.components')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getSlugForHomepage()
    {
        return $this->model->select('slug')
            ->where('homepage', true)
            ->with('related.components')
            ->first();
    }

    /**
     * @param string $slug
     * @param string $language
     * @param bool $withRelation
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getBySlug(string $slug, string $language, bool $withRelation = false)
    {
        $slug = strtolower($slug);

        $query = $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug\"")
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->whereNull('publish_at')->orWhere('publish_at', '<=', Carbon::now());
            });

        if ($withRelation) {
            $query->with('related.components');
        }

        return $query->firstOrFail();
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

    /**
     * @return Collection
     */
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
