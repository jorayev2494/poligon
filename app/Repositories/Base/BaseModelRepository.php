<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class BaseModelRepository
{

    private const GATE_DOESNT_RIGHTS_MSG = "you don't have enough rights";

    /**
    * @var Application $app
    */
    private Application $app;

    /**
    * @var Model $model
    */
    private Model $model;

    public function __construct()
    {
        $this->initializeModel();
    }

    private function initializeModel(): void
    {
        $this->model = resolve($this->getModel());
    }

    /**
     * @return string
     */
    abstract protected function getModel(): string;

    /**
     * @return Model
     */
    protected function getModeClone(): Model
    {
        return clone $this->model;
    }

    /**
     * @param array|string[] $columns
     * @return Collection
     */
    public function get(array $columns = ['*']): Collection
    {
        return $this->getModeClone()->newQuery()->get($columns);
    }

    /**
     * @param int $id
     * @param array|string[] $columns
     * @return Model
     */
    public function find(int $id, array $columns = ['*']): Model
    {
        return $this->getModeClone()->newQuery()->findOrFail($id, $columns);
    }

    /**
     * @param string $filed
     * @param string $value
     * @param array|string[] $columns
     * @return Model
     */
    public function findBy(string $filed, string $value, array $columns = ['*']): Model
    {
        return $this->getModeClone()->newQuery()->where($filed, $value)->firstOrFail($columns);
    }

    #region CRUD

    /**
     * @param array $data
     * @param bool $hasModelPolicy
     * @return Model
     */
    public function create(array $data, bool $hasModelPolicy = false, \Closure $closure = null): Model
    {
        return tap(
            $this->getModeClone()->newInstance(),
            function (Model $model) use($data, $hasModelPolicy, $closure): void {
                $this->tryRight($hasModelPolicy, 'create', $model, $closure);
                $model->fill($data)->save();
            }
        );
    }

    /**
     * @param int $id
     * @param array $data
     * @param bool $hasModelPolicy
     * @param \Closure|null $closure
     * @return Model
     */
    public function update(int $id, array $data, bool $hasModelPolicy = false, \Closure $closure = null): Model {

        if (array_key_exists('id', $data)) unset($data['id']);

        return tap(
            $this->find($id),
            function (Model $foundModel) use($data, $hasModelPolicy, $closure): void {
                $this->tryRight($hasModelPolicy, 'update', $foundModel, $closure);
                $foundModel->update($data);
            }
        );
    }

    /**
     * @param int $id
     * @param bool $hasModelPolicy
     * @param \Closure|null $closure
     * @return Model
     */
    public function delete(int $id, bool $hasModelPolicy = false, \Closure $closure = null): Model
    {
        return tap(
            $this->find($id),
            function (Model $foundModel) use($hasModelPolicy, $closure): void {
                $this->tryRight($hasModelPolicy, 'delete', $foundModel, $closure);
                $foundModel->delete();
            }
        );
    }
    #endregion

    private function tryRight(bool $hasModelPolicy, string $alias, Model $model, \Closure $closure = null): void
    {
        if ($hasModelPolicy && Gate::denies($alias, $model)) {
            $closure($model);
            throw new BadRequestException(self::GATE_DOESNT_RIGHTS_MSG);
        }
    }
}
