<?php
namespace App\Twig;

use Twig_Extension;

class Page extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_all_page_ids', function () {
                return get_all_page_ids();
            }),

            new \Twig_SimpleFunction('get_ancestors', function ($object_id = 0, $object_type = '', $resource_type = '') {
                return get_ancestors( $object_id, $object_type, $resource_type );
            }),

            new \Twig_SimpleFunction('get_page_link', function ($post = false, $leavename = false, $sample = false) {
                return get_page_link($post, $leavename, $sample);
            }),

            new \Twig_SimpleFunction('get_page_by_path', function ($page_path, $output = OBJECT, $post_type = 'page') {
                return get_page_by_path( $page_path, $output, $post_type );
            }),

            new \Twig_SimpleFunction('get_page_by_title', function ($page_title, $output = OBJECT, $post_type = 'page') {
                return get_page_by_title( $page_title, $output, $post_type );
            }),

            new \Twig_SimpleFunction('get_page_children', function ($page_id, $pages) {
                return get_page_children( $page_id, $pages );
            }),

            new \Twig_SimpleFunction('get_page_hierarchy', function (&$pages, $page_id = 0) {
                return get_page_hierarchy( $pages, $page_id );
            }),

            new \Twig_SimpleFunction('get_page_uri', function ($page_id = 0) {
                return get_page_uri( $page_id );
            }),

            new \Twig_SimpleFunction('get_pages', function ($args = array()) {
                return get_pages( $args );
            }),

            new \Twig_SimpleFunction('is_page', function ($page = '') {
                return is_page( $page );
            }),

            new \Twig_SimpleFunction('wp_link_pages', function ($args = '') {
                return wp_link_pages( $args );
            }),

            new \Twig_SimpleFunction('wp_list_pages', function ($args = '') {
                return wp_list_pages( $args );
            }),

            new \Twig_SimpleFunction('wp_page_menu', function ($args = array()) {
                return wp_page_menu( $args );
            }),

            new \Twig_SimpleFunction('wp_dropdown_pages', function ($args = '') {
                return wp_dropdown_pages( $args );
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