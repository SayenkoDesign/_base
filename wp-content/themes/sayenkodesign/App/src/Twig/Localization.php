<?php
namespace App\Twig;

use Twig_Extension;

class Localization extends Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('__', function($text, $domain = 'default'){
                return __( $text, $domain );
            }),

            new \Twig_SimpleFilter('_x', function($text, $context, $domain = 'default'){
                return _x( $text, $context, $domain );
            }),

            new \Twig_SimpleFilter('_x', function($single, $plural, $number, $domain = 'default'){
                return _n( $single, $plural, $number, $domain );
            }),

            new \Twig_SimpleFilter('_x', function($single, $plural, $number, $context, $domain = 'default'){
                return _nx( $single, $plural, $number, $context, $domain );
            }),

            new \Twig_SimpleFilter('esc_attr__', function($text, $domain = 'default'){
                return esc_attr__( $text, $domain );
            }),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_locale', function () {
                return get_locale();
            }),

            new \Twig_SimpleFunction('is_rtl', function () {
                return is_rtl();
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