<?php
namespace App\Twig;

use Twig_Extension;

class Shortcode extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('do_shortcode', function ($content, $ignore_html = false) {
                return do_shortcode($content, $ignore_html);
            }),

            new \Twig_SimpleFunction('get_shortcode_regex', function ($tagnames = null) {
                return get_shortcode_regex($tagnames);
            }),

            new \Twig_SimpleFunction('strip_shortcodes', function ($content) {
                return strip_shortcodes( $content );
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