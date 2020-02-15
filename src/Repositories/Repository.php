<?php

namespace clk528\NyuReport\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected $perPage = 20;
    /**
     * @var Model
     */
    private $model;

    /**
     * Repository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->perPage = (int)request('perPage', 20);
        $this->makeModel();
    }


    protected abstract function model(): string;

    /**
     * @param $model
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     * @throws \Exception
     */
    protected function setModel($model)
    {
        if ($model instanceof Model) {
            $this->model = $model;

            return $model;
        }

        $tempModel = app($model);

        if (!$tempModel instanceof Model) {
            throw new \Exception("class {$tempModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        $this->model = $tempModel;

        return $tempModel;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @throws \Exception
     */
    protected function makeModel()
    {
        $this->setModel($this->model());
    }

    /**
     * @param $id
     * @param array $columns
     * @param array $with
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'], $with = [])
    {
        $tempModel = $this->getBuilder();

        if ($with) {
            $tempModel->with($with);
        }

        return $tempModel->find($id, $columns);
    }

    /**
     * @param array $data
     * @param callable|null $callback
     * @return bool|Model|mixed
     */
    public function store(array $data, callable $callback = null)
    {
        $model = $this->getBuilder();

        if ($result = $model->create($data)) {
            if ($callback instanceof \Closure) {
                return $callback($result);
            }

            return $result;
        }

        return false;
    }

    /**
     * @param array $data
     * @param $id
     * @param callable|null $callback
     * @return bool|Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|mixed|null
     */
    public function update(array $data, $id, callable $callback = null)
    {
        $model = $this->find($id);
        if (is_null($model)) {
            return false;
        }
        if ($model->fill($data)->save()) {
            if ($callback instanceof \Closure) {
                return $callback($model);
            }

            return $model;
        }

        return false;
    }

    /**
     * @param $id
     * @param callable|null $callback
     * @return bool|Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|mixed|null
     * @throws \Exception
     */
    public function delete($id, callable $callback = null)
    {
        $model = $this->find($id);

        if (is_null($model)) {
            return false;
        }

        if ($model->delete()) {
            if ($callback instanceof \Closure) {
                return $callback($model);
            }

            return $model;
        }

        return false;
    }

    public function count($where = [], callable $callback = null): int
    {
        $query = $this->getBuilder();
        if ($where) {
            $query->where($where);
        }

        if ($callback) {
            $query = $callback($query);
        }

        return $query->count();
    }

    /**
     * @param array $columns
     * @param array $with
     * @param callable|null $builder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    function paginate($columns = ['*'], $with = [], callable $builder = null)
    {
        $query = $this->getModel()->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if ($builder instanceof \Closure) {
            $query = $builder($query);
        }


        return $query->paginate($this->perPage, $columns);
    }

    /**
     * @param array $columns
     * @param array $with
     * @param callable|null $builder
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    function noPaginate($columns = ['*'], $with = [], callable $builder = null)
    {
        $query = $this->getModel()->newQuery();

        if (!empty($with)) {
            $query->with($with);
        }

        if ($builder instanceof \Closure) {
            $query = $builder($query);
        }


        return $query->get($columns);
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getModel(), $name], $arguments);
    }
}
