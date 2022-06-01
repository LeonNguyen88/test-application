<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;

interface ElasticSearchRepositoryContract
{
    /**
     *
     * Index the data to elasticsearch
     *
     * @param Collection $data
     * @param string $index
     * @param array $searchable
     * @return mixed
     */
    public function index(Collection $data, string $index, array $searchable);


    /**
     * Flush index
     * @param string $index
     * @return void
     */
    public function flush(string $index);

    /**
     * Search multiple match
     * @param string $query
     * @param string $index
     * @param array $searchable
     * @return mixed
     */
    public function multipleMatch(string $query, string $index, array $searchable): array;
}
