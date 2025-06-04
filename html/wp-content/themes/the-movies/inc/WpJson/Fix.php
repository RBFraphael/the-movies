<?php

namespace WpTheme\WpJson;

use WP_REST_Request;
use WP_REST_Response;

class Fix extends BaseWpJson
{
    protected string $namespace = "the-movies/v1";
    protected string $endpoint = "fix";
    protected string $methods = "GET";

    public function handler(WP_REST_Request $request)
    {
        $totalDeleted = $this->deleteUnindentified();
        $totalUntitled = $this->fixUntitled();
        $totalIncomplete = $this->fixIncomplete();
        $totalDateFixed = $this->fixPostDates();

        return new WP_REST_Response([
            'deleted' => $totalDeleted,
            'untitled' => $totalUntitled,
            'incomplete' => $totalIncomplete,
            'dateFixed' => $totalDateFixed,
        ]);
    }

    private function fixPostDates()
    {
        $movies = get_posts([
            'post_type' => 'movie',
            'post_status' => 'any',
            'posts_per_page' => -1,
        ]);

        $fixed = 0;

        foreach($movies as $movie){
            $releaseDate = get_post_meta($movie->ID, 'tmdb_release_date', true);
            $publishDate = date('Y-m-d H:i:s', strtotime($releaseDate));

            wp_update_post([
                'ID' => $movie->ID,
                'post_date' => $publishDate
            ]);

            $fixed++;
        }

        return $fixed;
    }

    private function fixUntitled()
    {
        global $wpdb;

        $query = "SELECT ID FROM {$wpdb->posts} WHERE post_title = '' AND post_type = 'movie'";
        $ids = $wpdb->get_col($query);
        $fixQuery = "UPDATE {$wpdb->postmeta} SET meta_value = '0' WHERE meta_key = 'tmdb_processed' AND post_id = %d";

        $total = count($ids);

        foreach ($ids as $id) {
            $prepare = $wpdb->prepare($fixQuery, $id);
            $wpdb->query($prepare);
        }

        return $total;
    }

    private function fixIncomplete()
    {
        global $wpdb;

        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'tmdb_release_date' AND meta_value IS NULL";
        $ids = $wpdb->get_col($query);
        $fixQuery = "UPDATE {$wpdb->postmeta} SET meta_value = '0' WHERE meta_key = 'tmdb_processed' AND post_id = %d";

        $total = count($ids);

        foreach ($ids as $id) {
            $prepare = $wpdb->prepare($fixQuery, $id);
            $wpdb->query($prepare);
        }

        return $total;
    }

    private function deleteUnindentified()
    {
        global $wpdb;

        $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'tmdb_id' AND meta_value IS NULL";
        $ids = $wpdb->get_col($query);
        
        $total = count($ids);
        $metaDelete = "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d";
        $postDelete = "DELETE FROM {$wpdb->posts} WHERE ID = %d";

        foreach ($ids as $id) {
            $metaPrepare = $wpdb->prepare($metaDelete, $id);
            $wpdb->query($metaPrepare);

            $postPrepare = $wpdb->prepare($postDelete, $id);
            $wpdb->query($postPrepare);
        }

        return $total;
    }
}
