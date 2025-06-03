<?php

namespace WpTheme\WpJson;

use WP_REST_Request;
use WP_REST_Response;
use WpTheme\Lib\TMDB;

class ImportGenres extends BaseWpJson
{
    protected string $namespace = "the-movies/v1";
    protected string $endpoint = "import-genres";
    protected string $methods = "POST";

    public function handler(WP_REST_Request $request)
    {
        set_time_limit(0);
        update_option('tmdb_importing_genres', 1);

        $data = TMDB::getMovieGenres();

        $existingTerms = get_terms([
            'hide_empty' => false,
            'taxonomy' => 'genre'
        ]);
        $existingIds = [];
        foreach ($existingTerms as $term) {
            $existingIds[] = get_term_meta($term->term_id, 'tmdb_id', true);
        }

        $totalProcessed = 0;
        $limit = carbon_get_theme_option('tmdb_max_import_genres');
        
        foreach ($data['genres'] as $genreData) {
            if ($limit > 0 && $totalProcessed >= $limit) {
                break;
            }

            if (in_array($genreData['id'], $existingIds)) {
                continue;
            }

            $term = wp_insert_term($genreData['name'], 'genre');
            if (!is_wp_error($term)) {
                update_term_meta($term['term_id'], 'tmdb_id', $genreData['id']);
            }

            $totalProcessed++;
        }

        update_option('tmdb_importing_genres', 0);

        return new WP_REST_Response([
            'total_imported' => $totalProcessed
        ]);
    }
}
