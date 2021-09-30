<?php


namespace App\Services;


use App\Models\Author;
use App\Models\Book;

class BookService
{

    /**
     * @param array $data
     * @return Book
     */
    public function createBook(array $data) : Book
    {
        $bookName = $data['name'];
        $authors = explode(',', $data['authors']);
        $book = Book::create(['name' => $bookName, 'publisher_id' => $data['publisher_id'] ?? auth()->user()->publisher->id]);
        foreach ($authors as $author) {
            $dbAuthor = Author::where('author_name', trim($author))->first();
            if (!$dbAuthor && $author) {
                $dbAuthor = (new AuthorService)->createAuthor($author);
            }
            $book->authors()->attach($dbAuthor);
        }
        return $book;
    }

    /**
     * @param Book $book
     * @return string
     */
    public function getAuthorsString(Book $book) : string
    {
        $authors = '';
        $authorsCount = $book->authors->count();
        foreach ($book->authors as $key => $author) {
            $authors .= $author->author_name;
            if ($key + 1 < $authorsCount) {
                $authors .= ', ';
            }
        }
        return $authors;
    }

    /**
     * @param Book $book
     * @param string $name
     * @param string $authors
     * @return Book
     */
    public function updateBook(Book $book, string $name, string $authors) : Book
    {
        $updatedBook = $book->update(['name' => $name]);
        if ($updatedBook) {
            $authors = array_map('trim', explode(',', $authors));
            $this->detachAuthors($book, $authors);
            $this->attachAuthors($book, $authors);
        }
        return $book;
    }

    /**
     * @param Book $book
     * @param $authors
     */
    private function detachAuthors(Book $book, array $authors) : void
    {
        foreach ($book->authors as $author) {
            if (!in_array($author->author_name, $authors)) {
                $book->authors()->detach($author);
            }

            if (!$book->authors->contains($author)) {
                $book->authors()->attach($author);
            }
        }
    }

    /**
     * @param Book $book
     * @param $authors
     */
    private function attachAuthors(Book $book, array $authors) : void
    {
        foreach ($authors as $author) {
            $dbAuthor = Author::where(['author_name' => $author])->first();
            if (!$dbAuthor && $author) {
                $book->authors()->attach((new AuthorService)->createAuthor($author));
            }

            if (!$book->authors->contains($dbAuthor)) {
                $book->authors()->attach($dbAuthor);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBook($id)
    {
        return Book::find($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroyBook($id) : bool
    {
        $book = Book::find($id);

        if ($book) {
            $book->delete();
            return true;
        }
        return false;
    }
}
