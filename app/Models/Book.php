<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'summary',
        'title',
        'publisher',
    ];

    protected $primaryKey = 'id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * Get the user's first name.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function author(): Attribute
    {
        return Attribute::make(get: function ($value, $attributes) {
            $book = Book::find($attributes['id']);

            $authors = $book->authors()->get();

            return $authors->count() ? $authors->pluck('name')->toArray() : [];
        });
    }

}
