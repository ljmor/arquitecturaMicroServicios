<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;

class BookService
{
    use ConsumesExternalService;

    /**
     * @var string
     */
    public $baseUri;

    /**
     * @var string
     */
    public $secret;

    public function __construct()
    {
        $this->baseUri = env('BOOKS_SERVICE_BASE_URL');
        $this->secret = env('BOOKS_SERVICE_SECRET');

        if (empty($this->baseUri)) {
            throw new \RuntimeException('BOOKS_SERVICE_BASE_URL is not configured in .env file');
        }
    }

    /**
     * Get all books from the books service
     * @return array
     */
    public function obtainBooks()
    {
        return $this->performRequest('GET', '/books');
    }

    /**
     * Get a single book from the books service
     * @return array
     */
    public function obtainBook($book)
    {
        return $this->performRequest('GET', "/books/{$book}");
    }
}
