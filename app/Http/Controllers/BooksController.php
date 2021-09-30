<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BooksController extends Controller
{

    /**
     * BooksController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $books = Book::with(['authors' => function($q){
            $q->select(['id', 'author_name']);
        }, 'publisher'])->paginate(6);
        return view('books.index', compact('books'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * @param Book $book
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Book $book)
    {
        return view('books.create', compact('book'));
    }
}
