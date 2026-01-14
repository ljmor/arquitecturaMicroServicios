<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Interaction;
use App\Services\BookService;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    use ApiResponser;

    /**
     * @var BookService
     */
    public $bookService;

    /**
     * @var AuthorService
     */
    public $authorService;

    public function __construct(BookService $bookService, AuthorService $authorService)
    {
        $this->bookService = $bookService;
        $this->authorService = $authorService;
    }

    /**
     * Get popular books based on interaction count
     * @return Illuminate\Http\Response
     */
    public function popular(Request $request)
    {
        $limit = $request->input('limit', 5);

        $popularBookIds = Interaction::select('book_id', DB::raw('COUNT(*) as interaction_count'))
            ->groupBy('book_id')
            ->orderBy('interaction_count', 'desc')
            ->limit($limit)
            ->pluck('book_id')
            ->toArray();

        if (empty($popularBookIds)) {
            try {
                $allBooks = $this->bookService->obtainBooks();
                $books = array_slice($allBooks, 0, $limit);
                return $this->successResponse([
                    'type' => 'popular',
                    'message' => 'No interactions yet, showing latest books',
                    'books' => $books
                ]);
            } catch (\Exception $e) {
                return $this->errorResponse('Could not fetch books', Response::HTTP_SERVICE_UNAVAILABLE);
            }
        }

        $books = [];
        foreach ($popularBookIds as $bookId) {
            try {
                $book = $this->bookService->obtainBook($bookId);
                $interaction = Interaction::where('book_id', $bookId)->count();
                $book['interactions'] = $interaction;
                $books[] = $book;
            } catch (\Exception $e) {
                continue;
            }
        }

        return $this->successResponse([
            'type' => 'popular',
            'books' => $books
        ]);
    }

    /**
     * Get similar books based on the same author
     * @return Illuminate\Http\Response
     */
    public function similar($bookId)
    {
        try {
            $book = $this->bookService->obtainBook($bookId);
        } catch (\Exception $e) {
            return $this->errorResponse('Book not found', Response::HTTP_NOT_FOUND);
        }

        $authorId = $book['author_id'] ?? null;
        if (!$authorId) {
            return $this->errorResponse('Book has no author', Response::HTTP_BAD_REQUEST);
        }

        try {
            $allBooks = $this->bookService->obtainBooks();

            $similarBooks = array_filter($allBooks, function ($b) use ($authorId, $bookId) {
                return $b['author_id'] == $authorId && $b['id'] != $bookId;
            });

            $similarBooks = array_values($similarBooks);

            return $this->successResponse([
                'type' => 'similar',
                'based_on' => $book,
                'similar_books' => $similarBooks
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Could not fetch books', Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * Get books by a specific author
     * @return Illuminate\Http\Response
     */
    public function byAuthor($authorId)
    {
        try {
            $author = $this->authorService->obtainAuthor($authorId);
        } catch (\Exception $e) {
            return $this->errorResponse('Author not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $allBooks = $this->bookService->obtainBooks();

            $authorBooks = array_filter($allBooks, function ($book) use ($authorId) {
                return $book['author_id'] == $authorId;
            });

            $authorBooks = array_values($authorBooks);

            return $this->successResponse([
                'type' => 'by_author',
                'author' => $author,
                'books' => $authorBooks
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Could not fetch books', Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * Record a book interaction (view, click, etc.)
     * @return Illuminate\Http\Response
     */
    public function recordInteraction(Request $request)
    {
        $rules = [
            'book_id' => 'required|integer|min:1',
            'interaction_type' => 'in:view,click,purchase,wishlist',
        ];

        $this->validate($request, $rules);

        try {
            $this->bookService->obtainBook($request->book_id);
        } catch (\Exception $e) {
            return $this->errorResponse('Book not found', Response::HTTP_NOT_FOUND);
        }

        $interaction = Interaction::create([
            'book_id' => $request->book_id,
            'interaction_type' => $request->input('interaction_type', 'view'),
            'session_id' => $request->input('session_id'),
        ]);

        return $this->successResponse($interaction, Response::HTTP_CREATED);
    }

    /**
     * Get interaction statistics
     * @return Illuminate\Http\Response
     */
    public function stats()
    {
        $totalInteractions = Interaction::count();

        $interactionsByType = Interaction::select('interaction_type', DB::raw('COUNT(*) as count'))
            ->groupBy('interaction_type')
            ->get()
            ->pluck('count', 'interaction_type')
            ->toArray();

        $topBooks = Interaction::select('book_id', DB::raw('COUNT(*) as count'))
            ->groupBy('book_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        return $this->successResponse([
            'total_interactions' => $totalInteractions,
            'by_type' => $interactionsByType,
            'top_books' => $topBooks
        ]);
    }

    /**
     * Get all interactions (for debugging/admin)
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $interactions = Interaction::all();
        return $this->successResponse($interactions);
    }
}
