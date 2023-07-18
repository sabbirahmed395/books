<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::query();

        // check is there any filteration parameter
        if (request()->has('author') && strlen(request()->author) > 0) {
            $author = request()->author;
            $books = $books->where('author', 'LIKE', '%.$author.%');
        }

        $books = $books->get();

        return JsonResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookFormRequest $request)
    {
        $data = $request->validated(); // validation and return validated data
        $book = Book::create($data);

        return new JsonResource($book); // 201
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return new JsonResource($book); // 200
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookFormRequest $request, Book $book)
    {
        $book->fill($request->validated());
        $book->save(); // true or false

        return new JsonResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([], 204); // 204 - no content
    }
}
