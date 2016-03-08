<?php
namespace App\Twig;

use Twig_Extension;

class Category extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cat_is_ancestor_of', function ($cat1, $cat2) {
                return cat_is_ancestor_of( $cat1, $cat2 );
            }),

            new \Twig_SimpleFunction('get_ancestors', function ($object_id = 0, $object_type = '', $resource_type = '') {
                return get_ancestors( $object_id, $object_type, $resource_type );
            }),

            new \Twig_SimpleFunction('get_cat_ID', function ($cat_name) {
                return get_cat_ID( $cat_name );
            }),

            new \Twig_SimpleFunction('get_cat_name', function ($cat_id) {
                return get_cat_name( $cat_id );
            }),

            new \Twig_SimpleFunction('get_categories', function ($args = '') {
                return get_categories( $args );
            }),

            new \Twig_SimpleFunction('get_category', function ($category, $output = OBJECT, $filter = 'raw') {
                return get_category( $category, $output, $filter );
            }),

            new \Twig_SimpleFunction('get_category_by_path', function ($category_path, $full_match = true, $output = OBJECT) {
                return get_category_by_path( $category_path, $full_match, $output );
            }),

            new \Twig_SimpleFunction('get_category_by_slug', function ($slug) {
                return get_category_by_slug( $slug );
            }),

            new \Twig_SimpleFunction('get_the_category_by_ID', function ($cat_ID) {
                return get_the_category_by_ID( $cat_ID );
            }),

            new \Twig_SimpleFunction('get_the_category_by_ID', function ($separator = '', $parents='', $post_id = false) {
                return get_the_category_list( $separator, $parents, $post_id );
            }),

            new \Twig_SimpleFunction('get_category_link', function ($category_id) {
                return get_category_link( $category_id );
            }),

            new \Twig_SimpleFunction('get_category_parents', function ($id, $link = false, $separator = '/', $nicename = false, $visited = array()) {
                return get_category_parents( $id, $link, $separator, $nicename, $visited );
            }),

            new \Twig_SimpleFunction('get_the_category', function ($id = false) {
                return get_the_category( $id );
            }),

            new \Twig_SimpleFunction('single_cat_title', function ($prefix = '', $display = true) {
                return single_cat_title( $prefix, $display );
            }),

            new \Twig_SimpleFunction('in_category', function ($category, $post = null) {
                return in_category( $category, $post );
            }),

            new \Twig_SimpleFunction('is_category', function ($category = '') {
                return is_category( $category );
            }),

            new \Twig_SimpleFunction('wp_dropdown_categories', function ($args = '') {
                return wp_dropdown_categories( $args );
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