<?php

namespace WpTheme\CarbonFields\PostMetas;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class PostMeta extends BasePostMeta
{
    public function register()
    {
        Container::make('post_meta', __('Actor Info'))
            ->where('post_type', '=', 'actor')
            ->add_fields([
                Field::make('association', 'associated_movies', __("Associated Movies"))
                    ->set_types([['type' => 'post', 'post_type' => 'movie']])
            ]);
    }
}
