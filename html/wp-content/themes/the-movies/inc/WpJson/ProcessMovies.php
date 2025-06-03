<?php

namespace WpTheme\WpJson;

use WP_Post;
use WP_REST_Request;
use WP_REST_Response;
use WpTheme\Lib\TMDB;

class ProcessMovies extends BaseWpJson
{
    protected string $namespace = 'the-movies/v1';
    protected string $endpoint = 'process-movies';
    protected string $methods = 'POST';

    public function handler(WP_REST_Request $request)
    {
        set_time_limit(0);
        update_option('tmdb_processing_movies', 1);

        $movies = get_posts([
            'post_type' => "movie",
            'posts_per_page' => -1,
            'post_status' => "any",
            'meta_query' => [
                [ 'key' => 'tmdb_processed', 'value' => 0 ]
            ]
        ]);

        $processed = 0;

        foreach ($movies as $movie) {
            $this->processMovie($movie);
            $processed++;
        }

        update_option('tmdb_processing_movies', 0);

        return new WP_REST_Response([
            'processed' => $processed
        ]);
    }

    private function processMovie(WP_Post $movie)
    {
        $movieId = get_post_meta($movie->ID, 'tmdb_id', true);
        $movieData = TMDB::getMovieData($movieId);

        update_post_meta($movie->ID, 'tmdb_adult', $movieData['adult']);
        update_post_meta($movie->ID, 'tmdb_backdrop_path', $movieData['backdrop_path']);
        update_post_meta($movie->ID, 'tmdb_id', $movieData['id']);
        update_post_meta($movie->ID, 'tmdb_original_language', $movieData['original_language']);
        update_post_meta($movie->ID, 'tmdb_popularity', $movieData['popularity']);
        update_post_meta($movie->ID, 'tmdb_poster_path', $movieData['poster_path']);
        update_post_meta($movie->ID, 'tmdb_release_date', $movieData['release_date']);
        update_post_meta($movie->ID, 'tmdb_video', $movieData['video']);
        update_post_meta($movie->ID, 'tmdb_vote_average', $movieData['vote_average']);
        update_post_meta($movie->ID, 'tmdb_vote_count', $movieData['vote_count']);

        wp_update_post([
            'ID' => $movie->ID,
            'post_title' => $movieData['title'],
        ]);

        // require_once(ABSPATH . 'wp-admin/includes/image.php');
        // require_once(ABSPATH . 'wp-admin/includes/file.php');
        // require_once(ABSPATH . 'wp-admin/includes/media.php');

        // $genresIds = get_post_meta($movie->ID, 'tmdb_genre_ids', true);
        // $genres = get_terms([
        //     'taxonomy' => 'genre',
        //     'hide_empty' => false,
        //     'meta_key' => 'tmdb_id',
        //     'meta_value' => $genresIds
        // ]);
        // $ids = array_map(function ($genre) {
        //     return $genre->term_id;
        // }, $genres);
        // wp_set_object_terms($movie->ID, $ids, 'genre');

        // $image = get_post_meta($movie->ID, 'tmdb_poster_path', true);
        // if($image){
        //     $imageUrl = "https://image.tmdb.org/t/p/original" . $image;

        //     $download = download_url($imageUrl);
        //     if(!is_wp_error($download)){
        //         $fileArray = [
        //             'name' => basename($imageUrl),
        //             'tmp_name' => $download
        //         ];
        //         $attachment = media_handle_sideload($fileArray, $movie->ID, $movie->post_title . " - Movie poster");
        //         if(!is_wp_error($attachment)){
        //             set_post_thumbnail($movie->ID, $attachment);
        //         }
        //     }
        // }

        update_post_meta($movie->ID, 'tmdb_processed', 1);
    }
}