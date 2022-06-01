<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Author::factory(10000)->create();

        \App\Models\Book::factory(100000)->create()->each(function ($book) {
           $authorIds = Author::query()->inRandomOrder()->take(2)->pluck('id');


           $book->authors()->attach($authorIds);
           $book->save();
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
