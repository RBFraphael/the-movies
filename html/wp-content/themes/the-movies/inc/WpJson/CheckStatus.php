<?php

namespace WpTheme\WpJson;

use WP_REST_Request;
use WP_REST_Response;

class CheckStatus extends BaseWpJson
{
    protected string $namespace = "the-movies/v1";
    protected string $endpoint = "status";
    protected string $methods = "GET";

    public function handler(WP_REST_Request $request)
    {
        $importingActors = get_option('tmdb_importing_actors', 0) == 1;
        $importingGenres = get_option('tmdb_importing_genres', 0) == 1;
        $importingMovies = get_option('tmdb_importing_movies', 0) == 1;
        $processingActors = get_option('tmdb_processing_actors', 0) == 1;
        $processingMovies = get_option('tmdb_processing_movies', 0) == 1;

        return new WP_REST_Response([
            'importingActors' => $importingActors,
            'importingGenres' => $importingGenres,
            'importingMovies' => $importingMovies,
            'processingActors' => $processingActors,
            'processingMovies' => $processingMovies
        ]);
    }
}