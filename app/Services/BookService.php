<?php

namespace App\Services;

use App\Contracts\ElasticSearchRepositoryContract;
use App\Contracts\BookRepositoryContract;
use App\Models\Book;

class BookService
{
    private BookRepositoryContract $bookRepository;

    private ElasticSearchRepositoryContract $elasticSearchRepository;

    public function __construct(BookRepositoryContract $bookRepository, ElasticSearchRepositoryContract $elasticSearchRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->elasticSearchRepository = $elasticSearchRepository;
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->bookRepository->getAll();
    }

    /**
     * @param string $query
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(string $query = ''): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (!$query) {
            return $this->bookRepository->getByPagination();
        }
        $bookIds = $this->elasticSearchRepository->multipleMatch($query, 'books', ['title', 'publisher', 'summary', 'author']);

        return $this->bookRepository->findManyId($bookIds);
    }

}
