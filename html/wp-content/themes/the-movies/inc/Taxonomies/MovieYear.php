<?php

namespace WpTheme\Taxonomies;

class MovieYear extends BaseTaxonomy
{
    protected string $taxonomy = "movie_year";
    protected array|string $objectType = ["movie"];
    protected array|string $args = [
        'labels' => [
            'name' => 'Years',
            'singular_name' => 'Year',
        ],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true
    ];
}
