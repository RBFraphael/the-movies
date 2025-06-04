<?php

namespace WpTheme\Hooks;

use WpTheme\OptionsPages\BaseOptionsPage;

class WidgetsInit extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "widgets_init";

    public function run(...$args)
    {
        $this->registerMoviesSidebar();
    }

    private function registerMoviesSidebar()
    {
        register_sidebar([
            'name' => __("Movies sidebar", "the-movies"),
            'id' => "movies-sidebar",
            'before_widget' => '<li id="%1$s" class="widget %2$s sidebar__movies-sidebar__widget">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ]);
    }
}
