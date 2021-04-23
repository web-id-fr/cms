<?php

namespace Webid\LanguageTool\Repositories;

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
    public function store(Array $data): Model
    {
        return $this->model->create($data);
    }
}
