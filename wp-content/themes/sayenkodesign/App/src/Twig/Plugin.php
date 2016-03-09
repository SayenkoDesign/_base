<?php
namespace App\Twig;

use Twig_Extension;

class Plugin extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('plugins_url', function ($path = '', $plugin = '') {
                return plugins_url( $path, $plugin );
            }),

            new \Twig_SimpleFunction('get_admin_page_title', function () {
                return get_admin_page_title();
            }),

            new \Twig_SimpleFunction('is_plugin_active', function ($plugin) {
                return is_plugin_active($plugin);
            }),

            new \Twig_SimpleFunction('is_plugin_inactive', function ($plugin) {
                return is_plugin_inactive($plugin);
            }),

            new \Twig_SimpleFunction('get_plugins', function ($plugin_folder = '') {
                return get_plugins( $plugin_folder );
            }),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return __CLASS__;
    }
}