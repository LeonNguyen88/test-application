<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;


class ElasticSearchTest extends TestCase
{
    protected Collection $books;

    public function setUp(): void
    {
        parent::setUp();

    }

    /**
     * @group index
     */
    public function testRemoteHost()
    {
        $this
            ->get('http://'.config('test.elasticsearch_host'))
            ->assertOk();
    }

    /**
     *
     */
    public function testIndexBook()
    {
        $this
            ->artisan('index:book')
            ->expectsOutput('books were indexed to elasticsearch successfully')
            ->assertExitCode(0);
    }

}
