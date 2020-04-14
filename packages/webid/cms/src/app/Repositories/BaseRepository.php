<?php

namespace Webid\Cms\Src\App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function get(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $parameters
     *
     * @return Model
     */
    public function create(array $parameters)
    {
        return $this->model->create($parameters);
    }

    /**
     * @param Model $model
     * @param array $parameters
     *
     * @return bool|mixed
     */
    public function update(Model $model, array $parameters)
    {
        $model->fill($parameters);

        return $model->save() ? $model : false;
    }

    /**
     * @param Model $model
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }
}
