<?php
namespace App\Twig;

use Twig_Extension;

class Security extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('check_admin_referer', function () {
                return check_admin_referer();
            }),

            new \Twig_SimpleFunction('check_admin_referer', function ($action = -1, $query_arg = false, $die = true) {
                return check_ajax_referer( $action, $query_arg, $die );
            }),

            new \Twig_SimpleFunction('wp_create_nonce', function ($action = -1) {
                return wp_create_nonce( $action );
            }),

            new \Twig_SimpleFunction('wp_get_original_referer', function () {
                return wp_get_original_referer();
            }),

            new \Twig_SimpleFunction('wp_get_referer', function () {
                return wp_get_referer();
            }),

            new \Twig_SimpleFunction('wp_nonce_field', function ($action = -1, $name = "_wpnonce", $referer = true) {
                return wp_nonce_field( $action, $name, $referer, false );
            }),

            new \Twig_SimpleFunction('wp_nonce_url', function ($actionurl, $action = -1, $name = '_wpnonce') {
                return wp_nonce_url( $actionurl, $action, $name );
            }),

            new \Twig_SimpleFunction('wp_nonce_url', function ($jump_back_to = 'current') {
                return wp_original_referer_field( false, $jump_back_to );
            }),

            new \Twig_SimpleFunction('wp_referer_field', function () {
                return wp_referer_field( false );
            }),

            new \Twig_SimpleFunction('wp_verify_nonce', function ($nonce, $action = -1) {
                return wp_verify_nonce( $nonce, $action );
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