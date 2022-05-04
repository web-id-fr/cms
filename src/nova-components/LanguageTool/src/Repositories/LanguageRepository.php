<?php

namespace Webid\LanguageTool\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\LanguageTool\Models\Language;

class LanguageRepository
{
    /** @var Language */
    protected $model;

    /**
     * LanguageRepository constructor.
     *
     * @param Language $model
     */
    public function __construct(Language $model)
    {
        $this->model = $model;
    }

    /**
     * Enregistre une langue pour le site
     *
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function all(): Collection
    {
        return $this->model
            ->all();
    }

    /**
     * @param Model $language
     *
     * @return bool|null
     */
    public function delete(Model $language)
    {
        return $language->delete();
    }
}
