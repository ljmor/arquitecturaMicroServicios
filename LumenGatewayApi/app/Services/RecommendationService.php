<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;

class RecommendationService
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
        $this->baseUri = config('services.recommendations.base_uri');
        $this->secret = config('services.recommendations.secret');

        if (empty($this->baseUri)) {
            throw new \RuntimeException('RECOMMENDATIONS_SERVICE_BASE_URL is not configured in .env file');
        }
    }

    /**
     * Get popular book recommendations
     * @return array
     */
    public function getPopular($params = [])
    {
        return $this->performRequest('GET', '/recommendations/popular', $params);
    }

    /**
     * Get similar books for a given book
     * @return array
     */
    public function getSimilar($bookId)
    {
        return $this->performRequest('GET', "/recommendations/book/{$bookId}/similar");
    }

    /**
     * Get books by author
     * @return array
     */
    public function getByAuthor($authorId)
    {
        return $this->performRequest('GET', "/recommendations/author/{$authorId}");
    }

    /**
     * Get recommendation statistics
     * @return array
     */
    public function getStats()
    {
        return $this->performRequest('GET', '/recommendations/stats');
    }

    /**
     * Get all interactions
     * @return array
     */
    public function getInteractions()
    {
        return $this->performRequest('GET', '/interactions');
    }

    /**
     * Record a new interaction
     * @return array
     */
    public function recordInteraction($data)
    {
        return $this->performRequest('POST', '/interactions', $data);
    }
}
