<?php

namespace WpTheme\Taxonomies;

class MovieGenre extends BaseTaxonomy
{
    protected string $taxonomy = "genre";
    protected array|string $objectType = ["movie", "actor"];
    protected array|string $args = [
        'labels' => [
            'name' => 'Genres',
            'singular_name' => 'Genre',
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true
    ];
}
