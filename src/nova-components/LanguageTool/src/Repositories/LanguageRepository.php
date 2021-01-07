<?php

namespace Webid\LanguageTool\Repositories;

use Webid\Cms\App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Webid\LanguageTool\Models\Language;

class LanguageRepository extends BaseRepository
{
    /**
     * LanguageRepository constructor.
     *
     * @param Language $model
     */
    public function __construct(Language $model)
    {
        parent::__construct($model);
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
