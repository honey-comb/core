<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use HoneyComb\Core\Contracts\HCRepositoryContract;

/**
 * Class Repository
 * @package HoneyComb\Core\Repositories
 */
abstract class HCBaseRepository implements HCRepositoryContract
{
    /**
     *
     */
    const DEFAULT_PER_PAGE = 50;

    /**
     *
     */
    const DEFAULT_ATTRIBUTES_FIELD = 'id';

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->makeQuery()->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = self::DEFAULT_PER_PAGE, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->makeQuery()->paginate($perPage, $columns);
    }

    /**
     * Getting fillable items from model (if model extends Model)
     * @return array
     */
    public function getFillable (): array
    {
        return $this->makeModel()->getFillableFields();
    }

    /**
     * @param string $column
     * @return mixed
     */
    public function max(string $column = 'id')
    {
        return $this->makeQuery()->max($column);
    }

    /**
     * @param string $column
     * @return mixed
     */
    public function min(string $column = 'id')
    {
        return $this->makeQuery()->min($column);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data = []): Model
    {
        return $this->makeQuery()->create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data = []): bool
    {
        return $this->makeQuery()->insert($data);
    }

    /**
     * @param array $data
     * @param $attributeValue
     * @param string $attributeField
     * @return int
     */
    public function update(array $data, $attributeValue, string $attributeField = self::DEFAULT_ATTRIBUTES_FIELD): int
    {
        array_forget($data, [
            '_method',
            '_token',
        ]);

        return $this->makeQuery()->where($attributeField, $attributeValue)->update($data);
    }

    /**
     * @param array $data
     * @param $attributeValues
     * @param string $attributeField
     * @return int
     */
    public function updateWhereIn(
        array $data,
        $attributeValues,
        string $attributeField = self::DEFAULT_ATTRIBUTES_FIELD
    ): int {
        return $this->makeQuery()->whereIn($attributeField, $attributeValues)->update($data);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->makeQuery()->updateOrCreate($attributes, $values);
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function delete(array $criteria = [])
    {
        return $this->makeQuery()->where($criteria)->delete();
    }

    /**
     * @param $recordId
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function find($recordId, array $columns = ['*'])
    {
        return $this->makeQuery()->find($recordId, $columns);
    }

    /**
     * @param $recordId
     * @param array $columns
     * @return mixed|static
     */
    public function findAndLock($recordId, array $columns = ['*'])
    {
        return $this->makeQuery()->lockForUpdate()->find($recordId, $columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     * @return Model|null|static
     */
    public function findOneBy(array $criteria = [], array $columns = ['*'])
    {
        return $this->makeQuery()->where($criteria)->first($columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     * @return array|null|\stdClass
     */
    public function findOneByAndLock(array $criteria = [], array $columns = ['*'])
    {
        return $this->makeQuery()->where($criteria)->lockForUpdate()->first($columns);
    }

    /**
     * @param $recordId
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model
     */
    public function findOrFail($recordId, array $columns = ['*'])
    {
        return $this->makeQuery()->findOrFail($recordId, $columns);
    }

    /**
     * @param $recordId
     * @param array $columns
     * @return mixed
     */
    public function findAndLockOrFail($recordId, array $columns = ['*'])
    {
        return $this->makeQuery()->lockForUpdate()->findOrFail($recordId, $columns);
    }

    /**
     * @param array $criteria
     * @param array $columns
     * @return Collection
     */
    public function findAllBy(array $criteria = [], array $columns = ['*']): Collection
    {
        return $this->makeQuery()->select($columns)->where($criteria)->get();
    }

    /**
     * @param array $whereValues
     * @param string $whereField
     * @param array $columns
     * @return Collection
     */
    public function findWhereIn(array $whereValues = [], string $whereField = 'id', array $columns = ['*']): Collection
    {
        return $this->makeQuery()->select($columns)->whereIn($whereField, $whereValues)->get();
    }

    /**
     * @param array $data
     * @return int
     */
    public function insertGetId(array $data): int
    {
        return $this->makeQuery()->insertGetId($data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->makeQuery()->count();
    }

    /**
     * @return Model
     */
    final protected function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \RuntimeException(
                'Class ' . $this->model() . ' must be instance of HoneyComb\\Core\\Models\\HCModel'
            );
        }

        return $model;
    }

    /**
     * @return Builder
     */
    final public function makeQuery(): Builder
    {
        return $this->makeModel()->newQuery();
    }
}
