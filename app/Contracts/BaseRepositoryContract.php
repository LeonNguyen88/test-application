<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryContract
{
    /**
     * Set the relationships of the query.
     *
     * @param array $with
     * @return BaseRepositoryContract
     */
    public function with(array $with = []): BaseRepositoryContract;

    /**
     * Set withoutGlobalScopes attribute to true and apply it to the query.
     *
     * @return BaseRepositoryContract
     */
    public function withoutGlobalScopes(): BaseRepositoryContract;

    /**
     * Find a resource by id.
     *
     * @param string $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneById(string $id): Model;

    /**
     * Find a resource by key value criteria.
     *
     * @param array $criteria
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneBy(array $criteria): Model;

    /**
     * Get resources by the pagination
     *
     * @return LengthAwarePaginator
     */
    public function getByPagination(): LengthAwarePaginator;

    /**
     * Find many resources
     *
     * @param array $ids
     * @return LengthAwarePaginator
     */
    public function findManyId(array $ids): LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getAll(): Collection;
    /**
     * Save a resource.
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model;

    /**
     * Update a resource.
     *
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function update(Model $model, array $data): Model;
}
