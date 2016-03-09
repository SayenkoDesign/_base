<?php
namespace App\Twig;

use Twig_Extension;

class Comment extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_approved_comments', function ($post_id, $args = array()) {
                return get_approved_comments($post_id, $args);
            }),

            new \Twig_SimpleFunction('get_avatar', function ($id_or_email, $size = 96, $default = '', $alt = '', $args = null) {
                return get_avatar( $id_or_email, $size, $default, $alt, $args );
            }),

            new \Twig_SimpleFunction('get_comment', function (&$comment = null, $output = OBJECT) {
                return get_comment( $comment, $output );
            }),

            new \Twig_SimpleFunction('get_comment_text', function ($comment_ID = 0, $args = array()) {
                return get_comment_text( $comment_ID, $args );
            }),

            new \Twig_SimpleFunction('get_comment_meta', function ($comment_id, $key = '', $single = false) {
                return get_comment_meta( $comment_id, $key, $single );
            }),

            new \Twig_SimpleFunction('get_comments', function ($args = '') {
                return get_comments( $args );
            }),

            new \Twig_SimpleFunction('get_enclosed', function ($post_id) {
                return get_enclosed( $post_id );
            }),

            new \Twig_SimpleFunction('get_lastcommentmodified', function ($timezone = 'server') {
                return get_lastcommentmodified( $timezone );
            }),

            new \Twig_SimpleFunction('get_pung', function ($post_id) {
                return get_pung( $post_id );
            }),

            new \Twig_SimpleFunction('get_to_ping', function ($post_id) {
                return get_to_ping( $post_id );
            }),

            new \Twig_SimpleFunction('have_comments', function () {
                return have_comments();
            }),

            new \Twig_SimpleFunction('get_comment_author', function ($comment_ID = 0) {
                return get_comment_author( $comment_ID );
            }),

            new \Twig_SimpleFunction('is_trackback', function () {
                return is_trackback();
            }),

            new \Twig_SimpleFunction('trackback', function ($trackback_url, $title, $excerpt, $ID) {
                return trackback( $trackback_url, $title, $excerpt, $ID );
            }),

            new \Twig_SimpleFunction('trackback_url', function () {
                return trackback_url();
            }),

            new \Twig_SimpleFunction('wp_allow_comment', function ($commentdata) {
                return wp_allow_comment( $commentdata );
            }),

            new \Twig_SimpleFunction('wp_count_comments', function ($post_id = 0) {
                return wp_count_comments( $post_id );
            }),

            new \Twig_SimpleFunction('wp_get_comment_status', function ($comment_id) {
                return wp_get_comment_status( $comment_id );
            }),

            new \Twig_SimpleFunction('wp_get_current_commenter', function () {
                return wp_get_current_commenter();
            }),

            new \Twig_SimpleFunction('comment_class', function ($class = '', $comment = null, $post_id = null, $echo = true) {
                return comment_class( $class, $comment_id, $post_id, $echo );
            }),

            new \Twig_SimpleFunction('get_comment_date', function ($d = '', $comment_ID = 0) {
                return get_comment_date( $d, $comment_ID );
            }),

            new \Twig_SimpleFunction('get_comment_time', function ($d = '', $gmt = false, $translate = true) {
                return get_comment_time( $d, $gmt, $translate );
            }),

            new \Twig_SimpleFunction('paginate_comments_links', function ($args = array()) {
                return paginate_comments_links( $args );
            }),

            new \Twig_SimpleFunction('get_comment_pages_count', function ($comments, $per_page, $threaded) {
                return get_comment_pages_count($comments, $per_page, $threaded);
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