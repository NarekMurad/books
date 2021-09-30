<?php


namespace App\Services;


use App\Models\Author;

class AuthorService
{
    /**
     * @param string $name
     * @return Author
     */
    public function createAuthor(string $name) : Author
    {
        return Author::create(['author_name' => trim($name)]);
    }
}
