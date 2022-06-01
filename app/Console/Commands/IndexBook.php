<?php

namespace App\Console\Commands;

use App\Services\ElasticSearchService;
use App\Services\BookService;
use Illuminate\Console\Command;

class IndexBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index book data to the elasticsearch';

    /**
     * Execute the console command.
     *
     * @param ElasticSearchService $elasticSearchService
     * @param BookService $bookService
     */
    public function handle(ElasticSearchService $elasticSearchService, BookService $bookService)
    {
        $users = $bookService->getAll();
        // chunk books to index
        $users->chunk(100)->each(function ($data) use ($elasticSearchService) {
            try {
                $elasticSearchService->index($data, 'books', ['title', 'publisher', 'summary', 'author']);
            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        });
        $this->info('books were indexed to elasticsearch successfully');

    }
}
