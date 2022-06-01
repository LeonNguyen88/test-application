<?php

namespace App\Services;

use App\Contracts\ElasticSearchRepositoryContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ElasticSearchService
{
    private ElasticSearchRepositoryContract $elasticSearchRepository;

    public function __construct(ElasticSearchRepositoryContract $elasticSearchRepository)
    {
        $this->elasticSearchRepository = $elasticSearchRepository;
    }


    /**
     * @param Collection $data
     * @param string $index
     * @param array $searchable
     */
    public function index(Collection $data, string $index, array $searchable)
    {
        $this->elasticSearchRepository->index($data, $index, $searchable);
    }


    /**
     * @param string $query
     * @param string $index
     * @param array $searchable
     * @return mixed
     */
    public function multipleMatch(string $query, string $index, array $searchable): mixed
    {
        return $this->elasticSearchRepository->multipleMatch($query, $index, $searchable);
    }

    /**
     *
     * @param string $index
     * @return void
     */
    public function flush(string $index) {
        $this->elasticSearchRepository->flush($index);
    }

}
