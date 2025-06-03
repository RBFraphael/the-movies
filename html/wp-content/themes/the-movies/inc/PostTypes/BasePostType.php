<?php

namespace WpTheme\PostTypes;

abstract class BasePostType
{
    protected string $slug;
    protected array $configs;

    public function register() {
        register_post_type($this->slug, $this->configs);
    }
}
