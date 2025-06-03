<?php

namespace WpTheme\Hooks;

use WpTheme\CarbonFields\Blocks\BaseBlock;
use WpTheme\CarbonFields\OptionsPages\BaseOptionsPage;
use WpTheme\CarbonFields\PostMetas\BasePostMeta;

class CarbonFieldsRegisterFields extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "carbon_fields_register_fields";

    public function run()
    {
        $this->loadOptionsPages();
        $this->loadPostMetas();
        $this->loadBlocks();
    }

    private function loadBlocks()
    {
        $blocksDir = get_template_directory() . '/inc/CarbonFields/Blocks';
        $blocksClasses = glob($blocksDir . '/*.php');
        foreach ($blocksClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if($fileName == "BaseBlock"){ continue; }
            
            $fqcn = "WpTheme\\CarbonFields\\Blocks\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseBlock::class)) {
                $block = new $fqcn();
                $block->register();
            }
        }
    }

    private function loadOptionsPages()
    {
        $optionsPagesDir = get_template_directory() . '/inc/CarbonFields/OptionsPages';
        $optionsPagesClasses = glob($optionsPagesDir . '/*.php');
        foreach ($optionsPagesClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BaseOptionsPage") {
                continue;
            }

            $fqcn = "WpTheme\\CarbonFields\\OptionsPages\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseOptionsPage::class)) {
                $optionsPage = new $fqcn();
                $optionsPage->register();
            }
        }
    }

    private function loadPostMetas()
    {
        $postMetasDir = get_template_directory() . '/inc/CarbonFields/PostMetas';
        $postMetasClasses = glob($postMetasDir . '/*.php');
        foreach ($postMetasClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BasePostMeta") {
                continue;
            }

            $fqcn = "WpTheme\\CarbonFields\\PostMetas\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BasePostMeta::class)) {
                $postMeta = new $fqcn();
                $postMeta->register();
            }
        }
    }
}
