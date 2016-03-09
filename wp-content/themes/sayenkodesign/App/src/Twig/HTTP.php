<?php
namespace App\Twig;

use Twig_Extension;

class HTTP extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_remote_get', function ($url, $args = array()) {
                return wp_remote_get( $url, $args );
            }),

            new \Twig_SimpleFunction('wp_remote_retrieve_body', function ($response) {
                return wp_remote_retrieve_body($response);
            }),

            new \Twig_SimpleFunction('wp_get_http_headers', function ($url, $deprecated = false) {
                return wp_get_http_headers( $url, $deprecated );
            }),

            new \Twig_SimpleFunction('wp_remote_fopen', function ($uri) {
                return wp_remote_fopen($uri);
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