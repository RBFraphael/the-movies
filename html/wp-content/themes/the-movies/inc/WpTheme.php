<?php

namespace WpTheme;

use WpTheme\Hooks\BaseHook;

class WpTheme 
{
    public function __construct()
    {
        $hooksDir = get_template_directory() . '/inc/Hooks';
        $hooksClasses = glob($hooksDir . '/*.php');

        foreach ($hooksClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BasePostType") {
                continue;
            }

            $fqcn = "WpTheme\\Hooks\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseHook::class)) {
                $hook = new $fqcn();
                $hook->register();
            }
        }
    }
}
