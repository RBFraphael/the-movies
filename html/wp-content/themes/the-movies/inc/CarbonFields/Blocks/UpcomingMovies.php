<?php

namespace WpTheme\CarbonFields\Blocks;

use Carbon_Fields\Field;

class UpcomingMovies extends BaseBlock
{
    protected string $blockName = "Upcoming Movies";

    public function fields()
    {
        return [
            Field::make("text", "title", __("Title")),
            Field::make("text", "amount", __("Amount"))
                ->set_attribute("type", "number")
                ->set_attribute("min", "1")
                ->set_attribute("max", "10"),
        ];
    }

    public function render($fields, $attributes, $inner_blocks)
    {
        $movies = get_posts([
            'post_type' => 'movie',
            'post_status' => 'any',
            'posts_per_page' => $fields["amount"],
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        $movies = array_reverse($movies);
        
        return get_template_part("resources/views/blocks/upcoming-movies", null, [
            "fields" => $fields,
            "movies" => $movies
        ]);
    }
}
