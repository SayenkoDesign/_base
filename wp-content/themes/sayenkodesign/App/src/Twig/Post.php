<?php
namespace App\Twig;

use Twig_Extension;

class Post extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('adjacent_post', function ($in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'category') {
                return get_adjacent_post($in_same_term, $excluded_terms, $previous, $taxonomy);
            }),

            new \Twig_SimpleFunction('boundary_post', function ($in_same_cat = false, $excluded_categories = '', $start = true) {
                return get_boundary_post($in_same_cat, $excluded_categories, $start);
            }),

            new \Twig_SimpleFunction('children', function ($args = '', $output = OBJECT) {
                return get_children($args, $output);
            }),

            new \Twig_SimpleFunction('extended', function ($post) {
                return get_extended($post);
            }),

            new \Twig_SimpleFunction('next_post', function ($in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
                return get_next_post($in_same_term, $excluded_terms, $taxonomy);
            }),

            new \Twig_SimpleFunction('next_posts_link', function ($label = null, $max_page = 0) {
                return get_next_posts_link($label, $max_page);
            }),

            new \Twig_SimpleFunction('permalink', function ($post = 0, $leavename = false) {
                return get_permalink($post, $leavename);
            }),

            new \Twig_SimpleFunction('excerpt', function ($deprecated = '') {
                return get_the_excerpt($deprecated);
            }),

            new \Twig_SimpleFunction('thumbnail', function ($post = null, $size = 'post-thumbnail', $attr = '') {
                return get_the_post_thumbnail($post, $size, $attr);
            }),

            new \Twig_SimpleFunction('get_post', function ($post = null, $output = OBJECT, $filter = 'raw') {
                return get_post($post, $output, $filter);
            }),

            new \Twig_SimpleFunction('field', function ($field, $post, $context = 'display') {
                return get_post_field( $field, $post, $context );
            }),

            new \Twig_SimpleFunction('ancestors', function ($post) {
                return get_post_ancestors( $post );
            }),

            new \Twig_SimpleFunction('mime_type', function ($ID = '') {
                return get_post_mime_type( $ID );
            }),

            new \Twig_SimpleFunction('status', function ($ID = '') {
                return get_post_status( $ID );
            }),

            new \Twig_SimpleFunction('format', function ($post = null) {
                return get_post_format( $post );
            }),

            new \Twig_SimpleFunction('edit_post_link', function ($id = 0, $context = 'display') {
                return get_edit_post_link( $id, $context );
            }),

            new \Twig_SimpleFunction('delete_post_link', function ($id = 0, $deprecated = '', $force_delete = false) {
                return get_delete_post_link( $id, $deprecated, $force_delete );
            }),

            new \Twig_SimpleFunction('previous_post', function ($in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
                return get_previous_post( $in_same_term, $excluded_terms, $taxonomy );
            }),

            new \Twig_SimpleFunction('previous_posts_link', function ($label = null) {
                return get_previous_posts_link( $label );
            }),

            new \Twig_SimpleFunction('previous_posts_link', function ($args = null) {
                return get_posts( $args );
            }),

            new \Twig_SimpleFunction('have_posts', function () {
                return have_posts();
            }),

            new \Twig_SimpleFunction('is_single', function ($post = '') {
                return is_single();
            }),

            new \Twig_SimpleFunction('is_sticky', function ($post_id = 0) {
                return is_sticky($post_id);
            }),

            new \Twig_SimpleFunction('ID', function () {
                return get_the_ID();
            }),

            new \Twig_SimpleFunction('the_post', function () {
                the_post();
                return true;
            }),

            new \Twig_SimpleFunction('recent_posts', function ($args = array(), $output = ARRAY_A) {
                return wp_get_recent_posts( $args, $output );
            }),

            new \Twig_SimpleFunction('recent_posts', function ($post = null) {
                return has_post_thumbnail( $post );
            }),

            new \Twig_SimpleFunction('recent_posts', function ($id = 0) {
                return has_excerpt( $id );
            }),

            new \Twig_SimpleFunction('recent_posts', function ($format = array(), $post = null) {
                return has_post_format($format,$post);
            }),

            new \Twig_SimpleFunction('is_archive', function ($post_types = '') {
                return is_post_type_archive( $post_types );
            }),

            new \Twig_SimpleFunction('post_type_archive_title', function ($prefix = '') {
                return post_type_archive_title( $prefix, false );
            }),

            new \Twig_SimpleFunction('post_type_exists', function ($post_type) {
                return post_type_exists( $post_type );
            }),

            new \Twig_SimpleFunction('post_type', function ($post = null) {
                return get_post_type( $post );
            }),

            new \Twig_SimpleFunction('post_types', function ($args = array(), $output = 'names', $operator = 'and') {
                return get_post_types( $args, $output, $operator );
            }),

            new \Twig_SimpleFunction('archive_link', function ($post_type) {
                return get_post_type_archive_link( $post_type );
            }),

            new \Twig_SimpleFunction('capabilities', function ($args) {
                return get_post_type_capabilities( $args );
            }),

            new \Twig_SimpleFunction('is_hierarchical', function ($post_type) {
                return is_post_type_hierarchical( $post_type );
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