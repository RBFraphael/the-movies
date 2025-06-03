<?php

namespace WpTheme\OptionsPages;

abstract class BaseOptionsPage
{
    protected string $page_title;
    protected string $menu_title;
    protected string $capability = 'manage_options';
    protected string $menu_slug;
    protected int|null $position = null;

    public function register()
    {
        add_options_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            [$this, 'renderOptionsPage'],
            $this->position
        );
    }

    public abstract function renderOptionsPage();
}
