<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// Recommendations endpoints
$router->get('/recommendations/popular', 'RecommendationController@popular');
$router->get('/recommendations/book/{book}/similar', 'RecommendationController@similar');
$router->get('/recommendations/author/{author}', 'RecommendationController@byAuthor');
$router->get('/recommendations/stats', 'RecommendationController@stats');

// Interactions endpoints
$router->get('/interactions', 'RecommendationController@index');
$router->post('/interactions', 'RecommendationController@recordInteraction');
