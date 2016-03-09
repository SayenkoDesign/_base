<?php
namespace App\Twig;

use Twig_Extension;

class Option extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_site_option', function ($option, $default = false, $deprecated = true) {
                return get_site_option( $option, $default , $deprecated );
            }),

            new \Twig_SimpleFunction('get_site_url', function ($blog_id = null, $path = '', $scheme = null) {
                return get_site_url( $blog_id, $path, $scheme );
            }),

            new \Twig_SimpleFunction('get_admin_url', function ($blog_id = null, $path = '', $scheme = 'admin') {
                return get_admin_url( $blog_id, $path, $scheme );
            }),

            new \Twig_SimpleFunction('get_user_option', function ($option, $user = 0, $deprecated = '') {
                return get_user_option( $option, $user, $deprecated );
            }),

            new \Twig_SimpleFunction('get_option', function ($option, $user = 0, $deprecated = '') {
                return get_option( $option, $user = 0, $deprecated = '' );
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