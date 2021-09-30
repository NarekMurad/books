<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class AuthorBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::create(['name' => 'Баба-яга', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Волшебное кольцо', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Гуси-лебеди', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Зимовье зверей', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Иван-царевич и серый волк', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Колобок', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);
        Book::create(['name' => 'Лиса и Журавль', 'publisher_id' => Publisher::inRandomOrder()->first()->id]);

        Author::create(['author_name' => 'Михаил Лермонтов']);
        Author::create(['author_name' => 'Лев Толстой']);
        Author::create(['author_name' => 'Антон Чехов']);
        Author::create(['author_name' => 'Александр Блок']);
        Author::create(['author_name' => 'Николай Гоголь']);
        Author::create(['author_name' => 'Иван Тургенев']);
        Author::create(['author_name' => 'Иван Бунин']);
        Author::create(['author_name' => 'Николай Некрасов']);
        Author::create(['author_name' => 'Федор Достоевский']);
        Author::create(['author_name' => 'Борис Пастернак']);
        Author::create(['author_name' => 'Михаил Булгаков']);
        Author::create(['author_name' => 'Владимир Высоцкий']);
        Author::create(['author_name' => 'Александр Грибоедов']);
        Author::create(['author_name' => 'Максим Горький']);
        Author::create(['author_name' => 'Константин Паустовский']);
        Author::create(['author_name' => 'Николай Карамзин']);
        Author::create(['author_name' => 'Николай Лесков']);
        Author::create(['author_name' => 'Виктор Астафьев']);
        Author::create(['author_name' => 'Александр Солженицын']);
        Author::create(['author_name' => 'Самуил Маршак']);

        $books = Book::all();

        foreach ($books as $book) {
            $randNum = rand(1,5);
            for($i = 1; $i <= $randNum; $i++) {
                $author = Author::inRandomOrder()->first();
                if (!AuthorBook::where(['author_id' => $author->id, 'book_id' => $book->id])->first()) {
                    $book->authors()->attach($author);
                }
            }
        }
    }
}
