<?php
namespace App\Twig;

use Twig_Extension;

class Taxonomy extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_object_taxonomies', function ($object, $output = 'names') {
                return get_object_taxonomies( $object, $output );
            }),

            new \Twig_SimpleFunction('get_edit_term_link', function ($term_id, $taxonomy, $object_type = '') {
                return get_edit_term_link( $term_id, $taxonomy, $object_type );
            }),

            new \Twig_SimpleFunction('get_taxonomy', function ($taxonomy) {
                return get_taxonomy( $taxonomy );
            }),

            new \Twig_SimpleFunction('get_taxonomy', function ($taxonomy) {
                return get_taxonomy( $taxonomy );
            }),

            new \Twig_SimpleFunction('get_taxonomies', function ($args = array(), $output = 'names', $operator = 'and') {
                return get_taxonomies( $args, $output, $operator );
            }),

            new \Twig_SimpleFunction('get_term', function ($term, $taxonomy = '', $output = OBJECT, $filter = 'raw') {
                return get_term( $term, $taxonomy, $output, $filter );
            }),

            new \Twig_SimpleFunction('get_the_term_list', function ($term, $taxonomy = '', $output = OBJECT, $filter = 'raw') {
                return get_the_term_list( $id, $taxonomy, $before, $sep, $after );
            }),

            new \Twig_SimpleFunction('get_term_by', function ($field, $value, $taxonomy = '', $output = OBJECT, $filter = 'raw') {
                return get_term_by( $field, $value, $taxonomy, $output, $filter );
            }),

            new \Twig_SimpleFunction('get_the_terms', function ($post, $taxonomy) {
                return get_the_terms( $post, $taxonomy );
            }),

            new \Twig_SimpleFunction('get_term_children', function ($term_id, $taxonomy) {
                return get_term_children( $term_id, $taxonomy );
            }),

            new \Twig_SimpleFunction('get_term_link', function ($term, $taxonomy = '') {
                return get_term_link( $term, $taxonomy );
            }),

            new \Twig_SimpleFunction('get_terms', function ($taxonomies, $args = '') {
                return get_terms( $taxonomies, $args );
            }),

            new \Twig_SimpleFunction('is_tax', function ($taxonomy = '', $term = '') {
                return is_tax( $taxonomy, $term );
            }),

            new \Twig_SimpleFunction('is_taxonomy_hierarchical', function ($taxonomy) {
                return is_taxonomy_hierarchical( $taxonomy );
            }),

            new \Twig_SimpleFunction('taxonomy_exists', function ($taxonomy) {
                return taxonomy_exists( $taxonomy );
            }),

            new \Twig_SimpleFunction('term_exists', function ($term, $taxonomy = '', $parent = null) {
                return term_exists( $term, $taxonomy, $parent );
            }),

            new \Twig_SimpleFunction('wp_get_object_terms', function ($object_ids, $taxonomies, $args = array()) {
                return wp_get_object_terms( $object_ids, $taxonomies, $args );
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