<?php
namespace App\Twig;

use Twig_Extension;

class Terms extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_post_categories', function ($post_id = 0, $args = array()) {
                return wp_get_post_categories($post_id, $args);
            }),

            new \Twig_SimpleFunction('wp_get_post_tags', function ($post_id = 0, $args = array()) {
                return wp_get_post_tags( $post_id, $args );
            }),

            new \Twig_SimpleFunction('wp_get_post_terms', function ($post_id = 0, $taxonomy = 'post_tag', $args = array()) {
                return wp_get_post_terms( $post_id, $taxonomy, $args );
            }),

            new \Twig_SimpleFunction('wp_count_terms', function ($taxonomy, $args = array()) {
                return wp_count_terms( $taxonomy, $args );
            }),

            new \Twig_SimpleFunction('has_term', function ($term = '', $taxonomy = '', $post = null) {
                return has_term( $term, $taxonomy, $post );
            }),

            new \Twig_SimpleFunction('is_object_in_term', function ($object_id, $taxonomy, $terms = null) {
                return is_object_in_term( $object_id, $taxonomy, $terms = null );
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