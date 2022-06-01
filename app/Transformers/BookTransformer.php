<?php

namespace App\Transformers;

use App\Models\Book;
use Flugg\Responder\Transformers\Transformer;

class BookTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Book $book
     * @return array
     */
    public function transform(Book $book): array
    {
        return [
            'id' => $book->id,
            'publisher' => $book->publisher,
            'title' => $book->title,
            'summary' => $book->summary,
            'authors' => $book->author
        ];
    }
}
