<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Services\RecommendationService;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RecommendationController extends Controller
{
    use ApiResponser;

    /**
     * @var RecommendationService
     */
    public $recommendationService;

    /**
     * @var BookService
     */
    public $bookService;

    public function __construct(RecommendationService $recommendationService, BookService $bookService)
    {
        $this->recommendationService = $recommendationService;
        $this->bookService = $bookService;
    }

    /**
     * Get popular book recommendations
     * @return Illuminate\Http\Response
     */
    public function popular(Request $request)
    {
        $params = [];
        if ($request->has('limit')) {
            $params['limit'] = $request->input('limit');
        }

        return $this->successResponse($this->recommendationService->getPopular($params));
    }

    /**
     * Get similar books for a given book
     * @return Illuminate\Http\Response
     */
    public function similar($book)
    {
        return $this->successResponse($this->recommendationService->getSimilar($book));
    }

    /**
     * Get books by author
     * @return Illuminate\Http\Response
     */
    public function byAuthor($author)
    {
        return $this->successResponse($this->recommendationService->getByAuthor($author));
    }

    /**
     * Get recommendation statistics
     * @return Illuminate\Http\Response
     */
    public function stats()
    {
        return $this->successResponse($this->recommendationService->getStats());
    }

    /**
     * Get all interactions
     * @return Illuminate\Http\Response
     */
    public function interactions()
    {
        return $this->successResponse($this->recommendationService->getInteractions());
    }

    /**
     * Record a new interaction
     * @return Illuminate\Http\Response
     */
    public function recordInteraction(Request $request)
    {
        if ($request->has('book_id')) {
            try {
                $this->bookService->obtainBook($request->book_id);
            } catch (\Exception $e) {
                return $this->errorResponse('Book not found', Response::HTTP_NOT_FOUND);
            }
        }

        return $this->successResponse(
            $this->recommendationService->recordInteraction($request->all()),
            Response::HTTP_CREATED
        );
    }
}
