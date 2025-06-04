<?php

namespace WpTheme\WpJson;

use WP_REST_Request;
use WP_REST_Response;
use WpTheme\Lib\TMDB;

class ImportMovies extends BaseWpJson
{
    protected string $namespace = "the-movies/v1";
    protected string $endpoint = "import-movies";
    protected string $methods = "POST";

    public function handler(WP_REST_Request $request)
    {
        set_time_limit(0);
        update_option('tmdb_importing_movies', 1);

        $data = TMDB::getUpcomingMovies();
        $totalPages = $data['total_pages'];

        $totalProcessed = $this->addMovies($data['results']);
        $limit = carbon_get_theme_option('tmdb_max_import_movies') ?? 100;

        $page = 2;
        while ($page <= $totalPages) {
            if ($limit > 0 && $totalProcessed >= $limit) {
                break;
            }

            $data = TMDB::getUpcomingMovies($page);
            $totalProcessed += $this->addMovies($data['results']);
            $page++;
        }

        update_option('tmdb_importing_movies', 0);

        return new WP_REST_Response([
            'total_imported' => $totalProcessed
        ]);
    }

    private function addMovies($movies = [])
    {
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'movie'";
        $existingMovies = $wpdb->get_col($query);
        $query = "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'tmdb_id' AND post_id IN (" . implode(',', $existingMovies) . ")";
        $existingIds = $wpdb->get_col($query);

        $totalAdded = 0;

        foreach ($movies as $movieData) {
            if(!$this->isValid($movieData)) {
                continue;
            }

            if (in_array($movieData['id'], $existingIds)) {
                continue;
            }

            wp_insert_post([
                'post_title' => $movieData['title'],
                'post_content' => $movieData['overview'],
                'post_type' => "movie",
                'post_status' => "publish",
                'post_date' => date('Y-m-d H:i:s', strtotime($movieData['release_date'])),
                'meta_input' => [
                    'tmdb_processed' => 0,
                    'tmdb_adult' => $movieData['adult'] ? 1 : 0,
                    'tmdb_backdrop_path' => $movieData['backdrop_path'],
                    'tmdb_genre_ids' => $movieData['genre_ids'],
                    'tmdb_id' => $movieData['id'],
                    'tmdb_original_language' => $movieData['original_language'],
                    'tmdb_popularity' => $movieData['popularity'],
                    'tmdb_poster_path' => $movieData['poster_path'],
                    'tmdb_release_date' => $movieData['release_date'],
                    'tmdb_video' => $movieData['video'] ? 1 : 0,
                    'tmdb_vote_average' => $movieData['vote_average'],
                    'tmdb_vote_count' => $movieData['vote_count'],
                ]
            ]);
            $totalAdded++;
        }

        return $totalAdded;
    }

    private function isValid($movie): bool
    {
        $return = true;

        if(!isset($movie['id']) || !is_numeric($movie['id'])){
            $return = false;
        }

        if(!isset($movie['popularity']) || !is_numeric($movie['popularity'])){
            $return = false;
        }

        if(!isset($movie['poster_path']) || !is_string($movie['poster_path']) || strlen(trim($movie['poster_path'])) < 1){
            $return = false;
        }

        if(!isset($movie['release_date']) || !is_string($movie['release_date']) || strlen(trim($movie['release_date'])) < 1){
            $return = false;
        }

        if(!isset($movie['vote_average']) || !is_numeric($movie['vote_average'])){
            $return = false;
        }

        if(!isset($movie['vote_count']) || !is_numeric($movie['vote_count'])){
            $return = false;
        }

        return $return;
    }
}
