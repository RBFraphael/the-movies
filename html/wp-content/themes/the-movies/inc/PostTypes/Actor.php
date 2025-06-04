<?php

namespace WpTheme\PostTypes;

class Actor extends BasePostType
{
    protected string $slug = "actor";

    protected array $configs = [
        'labels' => [
            'name' => 'Actors',
            'singular_name' => 'Actor',
            'menu_name' => 'Actors',
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
        ],
        'taxonomies' => [
            'genre',
        ],
        'rewrite' => [
            'slug' => 'actors'
        ]
    ];
}
