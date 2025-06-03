<?php

namespace WpTheme\WpJson;

use WP_REST_Request;
use WP_REST_Response;
use WpTheme\Lib\TMDB;

class ImportActors extends BaseWpJson
{
    protected string $namespace = "the-movies/v1";
    protected string $endpoint = "import-actors";
    protected string $methods = "POST";

    public function handler(WP_REST_Request $request)
    {
        set_time_limit(0);

        update_option('tmdb_importing_actors', 1);
        
        $data = TMDB::getPopularPeople();
        $totalPages = $data['total_pages'];

        $totalProcessed = $this->addActors($data['results']);
        $limit = carbon_get_theme_option('tmdb_max_import_actors');

        $page = 2;
        while ($page <= $totalPages) {
            if ($limit > 0 && $totalProcessed >= $limit) { break; }
            $data = TMDB::getPopularPeople($page);
            $totalProcessed += $this->addActors($data['results']);
            $page++;
        }

        update_option('tmdb_importing_actors', 0);

        return new WP_REST_Response([
            'total_imported' => $totalProcessed
        ]);
    }

    private function addActors($actors = [])
    {
        $totalAdded = 0;

        $ids = array_map(function ($actor) {
            return $actor['id'];
        }, $actors);

        $existingPosts = get_posts([
            'post_type' => "actor",
            'posts_per_page' => -1,
            'post_status' => "any",
            'meta_query' => [
                [
                    'key' => 'tmdb_id',
                    'value' => $ids,
                    'compare' => 'NOT IN'
                ]
            ]
        ]);

        $existingIds = [];
        foreach ($existingPosts as $post) {
            $existingIds[] = get_post_meta($post->ID, 'tmdb_id', true);
        }

        foreach ($actors as $actorData) {
            if (in_array($actorData['id'], $existingIds)) {
                continue;
            }

            wp_insert_post([
                'post_title' => $actorData['name'],
                'post_content' => "",
                'post_type' => "actor",
                'post_status' => "publish",
                'meta_input' => [
                    'tmdb_processed' => 0,
                    'tmdb_adult' => $actorData['adult'] ? 1 : 9,
                    'tmdb_gender' => $actorData['gender'],
                    'tmdb_id' => $actorData['id'],
                    'tmdb_known_for_department' => $actorData['known_for_department'],
                    'tmdb_popularity' => $actorData['popularity'],
                    'tmdb_profile_path' => $actorData['profile_path'],
                    'tmdb_known_for' => $actorData['known_for'],
                ]
            ]);

            $this->addMovies($actorData['known_for']);

            $totalAdded++;
        }

        return $totalAdded;
    }

    private function addMovies($movies = [])
    {
        $totalAdded = 0;

        $ids = array_map(function ($movie) {
            return $movie['id'];
        }, $movies);

        $existingPosts = get_posts([
            'post_type' => "movie",
            'posts_per_page' => -1,
            'post_status' => "any",
            'meta_query' => [
                [
                    'key' => 'tmdb_id',
                    'value' => $ids,
                    'compare' => 'NOT IN'
                ]
            ]
        ]);

        $existingIds = [];
        foreach ($existingPosts as $post) {
            $existingIds[] = get_post_meta($post->ID, 'tmdb_id', true);
        }

        foreach ($movies as $movieData) {
            if (!$this->isMovieValid($movieData)) {
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

    private function isMovieValid($movie): bool
    {
        $return = true;

        if (!isset($movie['id']) || !is_numeric($movie['id'])) {
            $return = false;
        }

        if (!isset($movie['popularity']) || !is_numeric($movie['popularity'])) {
            $return = false;
        }

        if (!isset($movie['poster_path']) || !is_string($movie['poster_path']) || strlen(trim($movie['poster_path'])) < 1) {
            $return = false;
        }

        if (!isset($movie['release_date']) || !is_string($movie['release_date']) || strlen(trim($movie['release_date'])) < 1) {
            $return = false;
        }

        if (!isset($movie['vote_average']) || !is_numeric($movie['vote_average'])) {
            $return = false;
        }

        if (!isset($movie['vote_count']) || !is_numeric($movie['vote_count'])) {
            $return = false;
        }

        return $return;
    }
}
