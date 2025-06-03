<?php
if (!file_exists($autoload = get_template_directory() . '/vendor/autoload.php')) {
    wp_die(__('Error: Composer autoload.php not found. Run "composer install" inside theme folder.'));
}
require_once $autoload;

require_once get_template_directory() . '/inc/helpers.php';

new \WpTheme\WpTheme();
