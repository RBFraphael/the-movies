<?php

namespace WpTheme\Hooks;

class PreGetPosts extends BaseHook
{
    protected string $type = "filter";
    protected string $hook = "pre_get_posts";

    public function run(...$args)
    {
        $query = $args[0];

        if (!is_admin() && $query->is_main_query() && is_post_type_archive('movie')) {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
        }
    }
}
