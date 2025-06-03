<?php

namespace WpTheme\CarbonFields\Blocks;

use Carbon_Fields\Field;

class PopularActors extends BaseBlock
{
    protected string $blockName = "Popular Actors";

    public function fields()
    {
        return [
            Field::make("text", "title", __("Title")),
            Field::make("text", "amount", __("Amount"))
                ->set_attribute("type", "number")
        ];
    }

    public function render($fields, $attributes, $inner_blocks)
    {
        $actors = get_posts([
            'post_type' => "actor",
            'post_status' => "publish",
            'posts_per_page' => $fields["amount"],
            'meta_key' => 'tmdb_popularity',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ]);

        return get_template_part("resources/views/blocks/popular-actors", null, [
            "title" => $fields["title"],
            "actors" => $actors
        ]);
    }
}