<?php

namespace WpTheme\Hooks;

use WpTheme\WpJson\BaseWpJson;

class RestApiInit extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "rest_api_init";

    public function run(...$args)
    {
        $this->loadWpJsonEndpoints();
    }

    private function loadWpJsonEndpoints()
    {
        $endpointsDir = get_template_directory() . '/inc/WpJson';
        $endpointsClasses = glob($endpointsDir . '/*.php');
        foreach ($endpointsClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BaseWpJson") {
                continue;
            }

            $fqcn = "WpTheme\\WpJson\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseWpJson::class)) {
                $apiEndpoint = new $fqcn();
                $apiEndpoint->register();
            }
        }
    }
}
