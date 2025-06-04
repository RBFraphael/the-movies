<?php

namespace WpTheme\Hooks;

use WpTheme\PostTypes\BasePostType;
use WpTheme\Taxonomies\BaseTaxonomy;

class Init extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "init";

    public function run(...$args)
    {
        $this->registerPostTypes();
        $this->registerTaxonomies();
    }

    private function registerPostTypes()
    {
        $postTypesDir = get_template_directory() . '/inc/PostTypes';
        $postTypesClasses = glob($postTypesDir . '/*.php');
        foreach ($postTypesClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BasePostType") {
                continue;
            }

            $fqcn = "WpTheme\\PostTypes\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BasePostType::class)) {
                $postType = new $fqcn();
                $postType->register();
            }
        }
    }

    private function registerTaxonomies()
    {
        $taxonomiesDir = get_template_directory() . '/inc/Taxonomies';
        $taxonomiesClasses = glob($taxonomiesDir . '/*.php');
        foreach ($taxonomiesClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BaseTaxonomy") {
                continue;
            }

            $fqcn = "WpTheme\\Taxonomies\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseTaxonomy::class)) {
                $taxonomy = new $fqcn();
                $taxonomy->register();
            }
        }
    }
}
