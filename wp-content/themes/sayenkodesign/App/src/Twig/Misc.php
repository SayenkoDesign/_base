<?php
namespace App\Twig;

use Twig_Extension;

class Misc extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('admin_url', function ($path = '', $scheme = 'admin') {
                return admin_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('bool_from_yn', function ($yn) {
                return bool_from_yn( $yn );
            }),

            new \Twig_SimpleFunction('content_url', function ($path = '') {
                return content_url( $path );
            }),

            new \Twig_SimpleFunction('get_bloginfo', function ($show = '', $filter = 'raw') {
                return get_bloginfo( $show, $filter );
            }),

            new \Twig_SimpleFunction('get_bloginfo', function ($show = '', $filter = 'raw') {
                return get_bloginfo( $show, $filter );
            }),

            new \Twig_SimpleFunction('get_num_queries', function () {
                return get_num_queries();
            }),

            new \Twig_SimpleFunction('get_post_stati', function ($args = array(), $output = 'names', $operator = 'and') {
                return get_post_stati( $args, $output, $operator );
            }),

            new \Twig_SimpleFunction('get_post_statuses', function () {
                return get_post_statuses();
            }),

            new \Twig_SimpleFunction('get_query_var', function ($var, $default = '') {
                return get_query_var( $var, $default );
            }),

            new \Twig_SimpleFunction('home_url', function ($path = '', $scheme = null) {
                return home_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('includes_url', function () {
                return includes_url();
            }),

            new \Twig_SimpleFunction('is_blog_installed', function () {
                return is_blog_installed();
            }),

            new \Twig_SimpleFunction('is_main_site', function ($site_id = null) {
                return is_main_site( $site_id );
            }),

            new \Twig_SimpleFunction('is_main_query', function () {
                return is_main_query();
            }),

            new \Twig_SimpleFunction('is_multisite', function () {
                return is_multisite();
            }),

            new \Twig_SimpleFunction('is_ssl', function () {
                return is_ssl();
            }),

            new \Twig_SimpleFunction('is_wp_error', function ($thing) {
                return is_wp_error( $thing );
            }),

            new \Twig_SimpleFunction('network_admin_url', function ($path = '', $scheme = 'admin') {
                return network_admin_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('network_home_url', function ($path = '', $scheme = null) {
                return network_home_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('network_home_url', function ($path = '', $scheme = null) {
                return network_home_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('network_site_url', function ($path = '', $scheme = null) {
                return network_site_url( $path, $scheme );
            }),

            new \Twig_SimpleFunction('wp_cache_get', function ($key, $group = '', $force = false, &$found = null) {
                return wp_cache_get( $key, $group, $force, $found );
            }),

            new \Twig_SimpleFunction('wp_footer', function () {
                ob_start();
                wp_footer();
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('wp_head', function () {
                ob_start();
                wp_head();
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('wp_is_mobile', function () {
                return wp_is_mobile();
            }),

            new \Twig_SimpleFunction('wp_reset_postdata', function () {
                wp_reset_postdata();
                return;
            }),

            new \Twig_SimpleFunction('wp_reset_query', function ($location, $status = 302) {
                wp_safe_redirect( $location, $status );
                return;
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