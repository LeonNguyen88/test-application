<?php

namespace App\Providers;

use App\Contracts\ElasticSearchRepositoryContract;
use App\Contracts\BookRepositoryContract;
use App\Models\Book;
use App\Models\User;
use App\Repositories\ElasticSearchRepository;
use App\Repositories\BookRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     */
    protected bool $defer = true;

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BookRepositoryContract::class, function () {
            return new BookRepository(new Book());
        });

        $this->app->singleton(ElasticSearchRepositoryContract::class, function () {
            return new ElasticSearchRepository();
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [
            ElasticSearchRepositoryContract::class,
            BookRepositoryContract::class
        ];
    }
}
