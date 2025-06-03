<?php

namespace WpTheme\Hooks;

use WpTheme\OptionsPages\BaseOptionsPage;

class AdminMenu extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "admin_menu";

    public function run()
    {
        $this->loadOptionsPages();
    }

    private function loadOptionsPages()
    {
        $optionsPagesDir = get_template_directory() . '/inc/OptionsPages';
        $optionsPagesClasses = glob($optionsPagesDir . '/*.php');
        foreach ($optionsPagesClasses as $classFile) {
            $fileName = basename($classFile, '.php');

            if ($fileName == "BaseOptionsPage") {
                continue;
            }

            $fqcn = "WpTheme\\OptionsPages\\$fileName";

            if (class_exists($fqcn) && is_subclass_of($fqcn, BaseOptionsPage::class)) {
                $optionsPage = new $fqcn();
                $optionsPage->register();
            }
        }
    }
}
