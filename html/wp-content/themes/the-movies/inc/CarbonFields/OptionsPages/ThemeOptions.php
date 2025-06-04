<?php

namespace WpTheme\CarbonFields\OptionsPages;

use Carbon_Fields\Field;

class ThemeOptions extends BaseOptionsPage
{
    protected string $pageTitle = 'TMDB Sync';
    protected string $pageSlug = "tmdb-sync";

    public function fields()
    {
        return [
            Field::make('text', 'tmdb_api_key', __('TMDB API Key')),
            Field::make('text', 'tmdb_max_import_movies', __('Maximum number of upcoming movies to import (leave 0 to import all)'))
                ->set_help_text(__('This will not affect movies imported by importing Actors'))
                ->set_default_value(100)
                ->set_attribute("type", "number"),
            Field::make('text', 'tmdb_max_import_actors', __('Maximum number of popular actors to import (leave 0 to import all)'))
                ->set_help_text(__('When importing actors, all related movies will be imported as well'))
                ->set_default_value(100)
                ->set_attribute("type", "number"),
            Field::make('text', 'tmdb_max_import_genres', __('Maximum number of genres to import (leave 0 to import all)'))
                ->set_default_value(0)
                ->set_attribute("type", "number"),
        ];
    }

    public function afterForm()
    {
        $importingActors = get_option('tmdb_importing_actors', 0) == 1;
        $importingGenres = get_option('tmdb_importing_genres', 0) == 1;
        $importingMovies = get_option('tmdb_importing_movies', 0) == 1;
        $processingActors = get_option('tmdb_processing_actors', 0) == 1;
        $processingMovies = get_option('tmdb_processing_movies', 0) == 1;

        get_template_part('resources/views/admin/tmdb-sync', null, [
            'importingActors' => $importingActors,
            'importingGenres' => $importingGenres,
            'importingMovies' => $importingMovies,
            'processingActors' => $processingActors,
            'processingMovies' => $processingMovies
        ]);
    }
}
