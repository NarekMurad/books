<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('bearer')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::with(['authors' => function($q){
            $q->select(['id', 'author_name']);
        }, 'publisher'])->orderByDesc('created_at')->get();

        if ($books->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Books not found!'
            ])->setStatusCode(404, 'Books not found!');
        }

        return response()->json($books, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'authors', 'publisher_id']);

        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'authors' => ['required', 'string'],
            'publisher_id' => ['required', 'exists:publishers,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages()
            ])->setStatusCode(422, 'Books not found!');
        }

        $book = (new BookService)->createBook($data);

        return response()->json([
            'status' => true,
            'book' => $book
            ])->setStatusCode(201, 'Book created!');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $book = Book::with('authors')->where('id', $id)->first();

        if ($book) {
            return response()->json($book, 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Book not found!'
        ])->setStatusCode(404, 'Book not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookService $bookService, $id)
    {
        $data = $request->only(['name', 'authors']);

        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'authors' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->messages()
            ])->setStatusCode(422);
        }

        $book = $bookService->getBook($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Book not found!'
            ])->setStatusCode(404, 'Book not found!');
        }

        $bookService->updateBook($book, $request->name, $request->authors);;

        return response()->json([
            'status' => true,
            'message' => 'Book updated'
        ])->setStatusCode(200, 'Book updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookService $bookService, $id)
    {
        $bookDeleted = $bookService->destroyBook($id);

        if ($bookDeleted) {
            return response()->json([
                'status' => true,
                'message' => 'Book deleted'
            ])->setStatusCode(200, 'Book deleted!');
        }

        return response()->json([
            'status' => false,
            'message' => 'Book not found!'
        ])->setStatusCode(404, 'Book not found!');
    }
}
