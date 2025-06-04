<?php

namespace WpTheme\Hooks;

class AfterSetupTheme extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "after_setup_theme";

    public function run(...$args)
    {
        $this->bootCarbonFields();
        $this->registerNavMenus();
        $this->setSupportedFeatures();
    }

    private function bootCarbonFields()
    {
        \Carbon_Fields\Carbon_Fields::boot();
    }

    private function registerNavMenus()
    {
        register_nav_menus([
            'main_menu' => __("Main Menu"),
            'secondary_menu' => __("Secondary Menu"),
        ]);
    }

    private function setSupportedFeatures()
    {
        $supports = [
            'admin-bar',
            'block-templates',
            'custom-logo',
            'dark-editor-style',
            'html5',
            'menus',
            'post-thumbnails',
            'title-tag',
            'widgets',
            'widgets-block-editor',
            'wp-block-styles'
        ];

        foreach ($supports as $feature) {
            add_theme_support($feature);
        }
    }
}
