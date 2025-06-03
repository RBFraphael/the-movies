<?php

namespace WpTheme\WpJson;

use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

class ProcessActors extends BaseWpJson
{
    protected string $namespace = 'the-movies/v1';
    protected string $endpoint = 'process-actors';
    protected string $methods = 'POST';

    public function handler(WP_REST_Request $request)
    {
        set_time_limit(0);
        update_option('tmdb_processing_actors', 1);

        $movies = get_posts([
            'post_type' => "actor",
            'posts_per_page' => -1,
            'post_status' => "any",
            'meta_query' => [
                ['key' => 'tmdb_processed', 'value' => 0]
            ]
        ]);

        $processed = 0;

        foreach ($movies as $movie) {
            $this->processActor($movie);
            $processed++;
        }

        update_option('tmdb_processing_actors', 0);

        return new WP_REST_Response([
            'processed' => $processed
        ]);
    }

    private function processActor(WP_Post $actor)
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $image = get_post_meta($actor->ID, 'tmdb_profile_path', true);
        if ($image) {
            $imageUrl = "https://image.tmdb.org/t/p/original" . $image;

            $download = download_url($imageUrl);
            if (!is_wp_error($download)) {
                $fileArray = [
                    'name' => basename($imageUrl),
                    'tmp_name' => $download
                ];
                $attachment = media_handle_sideload($fileArray, $actor->ID, $actor->post_title . " - Profile Image");
                if (!is_wp_error($attachment)) {
                    set_post_thumbnail($actor->ID, $attachment);
                }
            }
        }

        $relatedMovies = get_post_meta($actor->ID, "tmdb_known_for", true);
        if($relatedMovies){
            $moviesIds = [];
            foreach($relatedMovies as $relatedMovie){
                $movies = get_posts([
                    'post_type' => "movie",
                    'posts_per_page' => 1,
                    'post_status' => "any",
                    'meta_query' => [
                        ['key' => 'tmdb_id', 'value' => $relatedMovie['id']]
                    ]
                ]);

                if(count($movies) > 0){
                    $moviesIds[] = $movies[0]->ID;
                } else {
                    $moviesIds[] = $this->addMovie($relatedMovie);
                }
            }

            carbon_set_post_meta($actor->ID, 'associated_movies', $moviesIds);
        }

        update_post_meta($actor->ID, 'tmdb_processed', 1);
    }

    private function addMovie($movieData)
    {
        $movie = wp_insert_post([
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

        return $movie;
    }
}
