<?php

namespace WpTheme\Taxonomies;

abstract class BaseTaxonomy
{
    protected string $taxonomy;
    protected array|string $objectType = "post";
    protected array|string $args = [];

    public function register()
    {
        register_taxonomy($this->taxonomy, $this->objectType, $this->args);
    }
}
