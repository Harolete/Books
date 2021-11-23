<?php

namespace App\Http\Controllers;

use App\Book;
use App\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Retorna una lista de libros
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successResponse(Book::all());
    }

    /**
     * Crea una instancia de Book
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'title'         =>'required|max:255',
            'description'   =>'required|max:255',
            'price'         =>'required|min:1',
            'author_id'     =>'required|min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);

    }

    /**
     * Retorna un author especifico
     * @param  $author
     * @return JsonResponse
     */
    public function show($book)
    {
        $book = Book::findOrFail($book);

        return $this->successResponse($book, Response::HTTP_OK);
    }

    /**
     * Modifica un authjor especifico
     * @param Request $request
     * @param  $author
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $book)
    {
        $rules = [
            'title'         =>'max:255',
            'description'   =>'max:255',
            'price'         =>'min:1',
            'author_id'     =>'min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($book);

        $book->fill($request->all());

        if($book->isclean()){
            return $this->errorResponse('al menos un dato debe cambiar',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();

        return $this->successResponse($book, Response::HTTP_OK);

    }

    /**
     * Retorna un author especifico
     * @param  $author
     * @return JsonResponse
     */
    public function destroy($book)
    {
        $book = Book::findOrFail($book);

        $book ->delete();

        return $this->successResponse($book, Response::HTTP_OK);

    }
}
