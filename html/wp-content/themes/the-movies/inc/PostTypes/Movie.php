<?php

namespace WpTheme\PostTypes;

class Movie extends BasePostType
{
    protected string $slug = "movie";

    protected array $configs = [
        'labels' => [
            'name' => 'Movies',
            'singular_name' => 'Movie',
            'menu_name' => 'Movies',
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'capability_type' => 'post',
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
        ],
        'taxonomies' => [
            'genre', 'movie_year'
        ],
        'rewrite' => [
            'slug' => 'movies'
        ]
    ];
}
