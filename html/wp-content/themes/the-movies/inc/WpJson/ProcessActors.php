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

        $actors = get_posts([
            'post_type' => "actor",
            'posts_per_page' => -1,
            'post_status' => "any",
            'meta_query' => [
                ['key' => 'tmdb_processed', 'value' => 0]
            ]
        ]);

        $processed = 0;

        foreach ($actors as $actor) {
            $this->processActor($actor);
            $processed++;
        }

        update_option('tmdb_processing_actors', 0);

        return new WP_REST_Response([
            'processed' => $processed
        ]);
    }

    private function processActor(WP_Post $actor)
    {
        // $this->importActorImage($actor);
        $this->addActorMovies($actor);

        update_post_meta($actor->ID, 'tmdb_processed', 1);
    }

    private function importActorImage(WP_Post $actor)
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
    }

    private function addActorMovies(WP_Post $actor)
    {
        global $wpdb;

        $relatedMovies = get_post_meta($actor->ID, "tmdb_known_for", true);
        $relatedMoviesIds = array_map(function ($movie) {
            return $movie['id'];
        }, $relatedMovies);

        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'tmdb_id' AND meta_value IN (" . implode(',', $relatedMoviesIds) . ")";
        $moviesIds = $wpdb->get_col($query);

        carbon_set_post_meta($actor->ID, 'associated_movies', $moviesIds);
    }
}
