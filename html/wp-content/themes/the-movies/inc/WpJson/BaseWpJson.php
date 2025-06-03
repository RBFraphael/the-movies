<?php

namespace WpTheme\WpJson;

use WP_REST_Request;

abstract class BaseWpJson
{
    protected string $namespace;
    protected string $endpoint;
    protected bool $override = false;
    protected string $methods = "GET";

    public function register()
    {
        register_rest_route(
            $this->namespace,
            $this->endpoint,
            [
                'methods' => explode("|", $this->methods),
                'callback' => [$this, 'handler'],
                'permission_callback' => '__return_true',
            ],
            $this->override
        );
    }

    abstract public function handler(WP_REST_Request $request);
}