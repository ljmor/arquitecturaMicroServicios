<?php

return [
    'authors' => [
        'base_uri' => env('AUTHORS_SERVICE_BASE_URL'),
        'secret' => env('AUTHORS_SERVICE_SECRET'),
    ],

    'books' => [
        'base_uri' => env('BOOKS_SERVICE_BASE_URL'),
        'secret' => env('BOOKS_SERVICE_SECRET'),
    ],

    'recommendations' => [
        'base_uri' => env('RECOMMENDATIONS_SERVICE_BASE_URL'),
        'secret' => env('RECOMMENDATIONS_SERVICE_SECRET'),
    ],
];
