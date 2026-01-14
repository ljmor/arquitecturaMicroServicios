<?php

class ExampleTest extends TestCase
{
    /**
     * Test popular recommendations endpoint returns successful response.
     *
     * @return void
     */
    public function testPopularEndpoint()
    {
        $this->get('/recommendations/popular');

        $this->assertResponseStatus(200);
    }

    /**
     * Test stats endpoint returns successful response.
     *
     * @return void
     */
    public function testStatsEndpoint()
    {
        $this->get('/recommendations/stats');

        $this->assertResponseStatus(200);
    }
}
