<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class BookControllerTest extends TestCase
{
    protected Collection $books;

    private int $perPage = 15;

    public function setUp(): void
    {
        parent::setUp();
        $this->books = Book::query()->get();

    }

    /**
     * @group index
     * @group crud
     */
    public function testRouteIndex()
    {
        $this
            ->getJson(route('books.index'))
            ->assertOk();
    }

    /**
     * @group index
     * @group crud
     */
    public function testStructureIndex()
    {
        $this
            ->getJson(route('books.index'))
            ->assertJsonStructure([
                'status',
                'success',
                'data',
                'pagination'
            ])
            ->assertOk();
    }

    /**
     * @group index
     * @group crud
     */
    public function testCountPagination()
    {
        $this
            ->getJson(route('books.index'))
            ->assertJsonCount($this->perPage, 'data')
            ->assertOk();
    }

    /**
     * @return void
     */
    public function testCountBook()
    {
        $json = $this
            ->getJson(route('books.index'));

        $this->assertEquals(Arr::get($json, 'pagination.total'), $this->books->count());
    }

    /**
     * @return void
     */
    public function testStatusIndex()
    {
        $this
            ->getJson(route('books.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testArrayBook()
    {
        $json = $this
            ->getJson(route('books.index'));
        $book = Arr::first(Arr::get($json, 'data', []));

        $this->assertEquals(['id', 'publisher', 'title', 'summary', 'authors'], array_keys($book));
    }

    /**
     * @return void
     */
    public function testEmptyQuery()
    {
        $this
            ->getJson(route('books.index') . '?q=')
            ->assertOk();
    }

    /**
     * @return void
     */
    public function testCountBookEmptyQuery()
    {
        $json = $this
            ->getJson(route('books.index') . '?q=');
        $this->assertEquals($this->books->count(), Arr::get($json, 'pagination.total'));
    }

    public function testRouteIndexIncludeQuery()
    {
        Artisan::call('index:book');
        $this
            ->getJson(route('books.index') . '?q=abc')
            ->assertOk();
    }
}
