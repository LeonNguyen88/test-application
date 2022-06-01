<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Models\Author;
use App\Models\Book;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Author::factory(100)->create();
        Book::factory(1000)->create()->each(function ($book) {
            $authorIds = Author::query()->inRandomOrder()->take(2)->pluck('id');

            $book->authors()->attach($authorIds);
            $book->save();
        });

        Artisan::call('config:clear');
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }

            public function report(Exception $exception)
            {
            }

            public function render($request, Exception $exception)
            {
                throw $exception;
            }
        });
    }
}
