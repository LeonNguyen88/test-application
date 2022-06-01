<?php

namespace App\Console\Commands;

use App\Services\ElasticSearchService;
use App\Services\BookService;
use Illuminate\Console\Command;

class FlushIndexBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush-index:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush index book to elasticsearch';

    /**
     * Execute the console command.
     *
     * @param ElasticSearchService $elasticSearchService
     * @param BookService $bookService
     */
    public function handle(ElasticSearchService $elasticSearchService, BookService $bookService)
    {
        try {
            $elasticSearchService->flush('books');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
        $this->info('flush successfully');
    }
}
