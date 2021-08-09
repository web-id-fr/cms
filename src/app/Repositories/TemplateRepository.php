<?php

namespace Webid\Cms\App\Repositories;

use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TemplateRepository
{
    /** @var Template */
    protected $model;

    /**
     * TemplateRepository constructor.
     *
     * @param Template $model
     */
    public function __construct(Template $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getPublishedTemplates()
    {
        return $this->model
            ->where('status', Template::_STATUS_PUBLISHED)
            ->with('related.components')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getHomepageId()
    {
        return $this->model->select('id')
            ->where('homepage', true)
            ->first();
    }

    /**
     *
     * @return mixed
     *
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
     *
     * @return Template
     */
    public function getBySlug(string $slug, string $language): Template
    {
        $slug = strtolower($slug);

        return $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug\"")
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->whereNull('publish_at')->orWhere('publish_at', '<=', Carbon::now());
            })
            ->firstOrFail();
    }

    /**
     * @param string $slug
     * @param string $language
     *
     * @return Template
     */
    public function getBySlugWithRelations(string $slug, string $language): Template
    {
        $slug = strtolower($slug);

        return $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug\"")
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->whereNull('publish_at')->orWhere('publish_at', '<=', Carbon::now());
            })->with('related.components')
            ->firstOrFail();
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

    /**
     * @return Collection<Template>
     */
    public function getPublicPagesContainingArticlesLists(): Collection
    {
        return $this->model
            ->where('status', Template::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->orWhere('publish_at', '<', now())
                    ->orWhereNull('publish_at');
            })
            ->where('indexation', true)
            ->where('contains_articles_list', true)
            ->get();
    }
}
