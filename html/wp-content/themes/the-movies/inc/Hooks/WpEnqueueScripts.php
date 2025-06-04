<?php

namespace WpTheme\Hooks;

class WpEnqueueScripts extends BaseHook
{
    protected string $type = "action";
    protected string $hook = "wp_enqueue_scripts";

    public function run(...$args)
    {
        $this->styles();
        $this->scripts();
    }

    private function getViteManifest()
    {
        $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';
        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        return $manifest;
    }

    private function styles()
    {
        $manifest = $this->getViteManifest();
        if (isset($manifest['scss/main.scss']['file'])) {
            wp_enqueue_style(
                'mytheme-style',
                get_template_directory_uri() . '/dist/' . $manifest['scss/main.scss']['file'],
                [],
                null
            );
        }
    }

    private function scripts()
    {
        $manifest = $this->getViteManifest();
        if (isset($manifest['js/main.ts']['file'])) {
            wp_enqueue_script(
                'mytheme-script',
                get_template_directory_uri() . '/dist/' . $manifest['js/main.ts']['file'],
                [],
                null,
                true
            );
        }
    }
}
