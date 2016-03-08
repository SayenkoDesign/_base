<?php
namespace App\Twig;

use Twig_Extension;

class Fields extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_post_custom', function ($post_id = 0) {
                return get_post_custom($post_id);
            }),

            new \Twig_SimpleFunction('get_post_custom_keys', function ($post_id = 0) {
                return get_post_custom_keys($post_id);
            }),

            new \Twig_SimpleFunction('get_post_custom_values', function ($key = '', $post_id = 0) {
                return get_post_custom_values($key, $post_id);
            }),

            new \Twig_SimpleFunction('get_post_meta', function ($post_id, $key = '', $single = false) {
                return get_post_meta($post_id, $key, $single);
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