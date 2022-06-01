<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Transformers\BookTransformer;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private BookService $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @param Request $request
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse|\Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $query = $request->get('q') ?: '';
        try {
            $response = $this->bookService->index($query);
        } catch (\Exception $e) {
            return $this->httpBadRequest($e->getMessage());
        }

        return $this->httpOk($response, BookTransformer::class);
    }
}
