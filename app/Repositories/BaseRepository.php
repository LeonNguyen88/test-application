<?php

namespace App\Repositories;

use App\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

abstract class BaseRepository implements BaseRepositoryContract
{
    protected Model $model;

    protected bool $withoutGlobalScopes = false;

    protected array $with = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function with(array $with = []): BaseRepository
    {
        $this->with = $with;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutGlobalScopes(): BaseRepository
    {
        $this->withoutGlobalScopes = true;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $data): Model
    {
        return tap($model)->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getByPagination(): LengthAwarePaginator
    {
        return $this->model->with($this->with)
            ->orderByDesc('created_at')->paginate();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->with($this->with)
            ->orderByDesc('created_at')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById(string $id): Model
    {
        if (!Uuid::isValid($id)) {
            throw (new ModelNotFoundException())->setModel(get_class($this->model));
        }

        if (!empty($this->with) || auth()->check()) {
            return $this->findOneBy(['id' => $id]);
        }

        return Cache::remember($id, now()->addHour(), function () use ($id) {
            return $this->findOneBy(['id' => $id]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findManyId(array $ids): LengthAwarePaginator
    {
        return $this->model->query()->whereIn('id', $ids)
            ->orderByDesc('created_at')
            ->paginate();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria): Model
    {
        if (!$this->withoutGlobalScopes) {
            return $this->model->with($this->with)
                ->where($criteria)
                ->orderByDesc('created_at')
                ->firstOrFail();
        }

        return $this->model->with($this->with)
            ->withoutGlobalScopes()
            ->where($criteria)
            ->orderByDesc('created_at')
            ->firstOrFail();
    }
}
