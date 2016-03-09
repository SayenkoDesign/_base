<?php
namespace App\Twig;

use Twig_Extension;

class Conditional extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('has_nav_menu', function ($location) {
                return has_nav_menu( $location );
            }),

            new \Twig_SimpleFunction('has_tag', function ($tag = '', $post = null) {
                return has_tag( $tag, $post );
            }),

            new \Twig_SimpleFunction('is_category', function ($category = '') {
                return is_category( $category );
            }),

            new \Twig_SimpleFunction('is_404', function () {
                return is_404();
            }),

            new \Twig_SimpleFunction('is_admin', function () {
                return is_admin();
            }),

            new \Twig_SimpleFunction('is_archive', function () {
                return is_archive();
            }),

            new \Twig_SimpleFunction('is_attachment', function () {
                return is_attachment();
            }),

            new \Twig_SimpleFunction('is_author', function ($author = '') {
                return is_author($author);
            }),

            new \Twig_SimpleFunction('is_category', function ($category = '') {
                return is_category($category);
            }),

            new \Twig_SimpleFunction('is_comments_popup', function () {
                return is_comments_popup();
            }),

            new \Twig_SimpleFunction('is_customize_preview', function () {
                return is_customize_preview();
            }),

            new \Twig_SimpleFunction('is_feed', function ($feeds = '') {
                return is_feed( $feeds );
            }),

            new \Twig_SimpleFunction('is_front_page', function () {
                return is_front_page();
            }),

            new \Twig_SimpleFunction('is_home', function () {
                return is_home();
            }),

            new \Twig_SimpleFunction('is_page', function () {
                return is_page();
            }),

            new \Twig_SimpleFunction('is_page_template', function () {
                return is_page_template();
            }),

            new \Twig_SimpleFunction('is_paged', function () {
                return is_paged();
            }),

            new \Twig_SimpleFunction('is_preview', function () {
                return is_preview();
            }),

            new \Twig_SimpleFunction('is_search', function () {
                return is_search();
            }),

            new \Twig_SimpleFunction('is_search', function ($post = '') {
                return is_single($post);
            }),

            new \Twig_SimpleFunction('is_sticky', function ($post_id = 0) {
                return is_sticky($post_id);
            }),

            new \Twig_SimpleFunction('is_tag', function ($tag = '') {
                return is_tag( $tag );
            }),

            new \Twig_SimpleFunction('is_trackback', function () {
                return is_trackback();
            }),

            new \Twig_SimpleFunction('is_date', function () {
                return is_date();
            }),

            new \Twig_SimpleFunction('is_year', function () {
                return is_year();
            }),

            new \Twig_SimpleFunction('is_month', function () {
                return is_month();
            }),

            new \Twig_SimpleFunction('is_day', function () {
                return is_day();
            }),

            new \Twig_SimpleFunction('is_time', function () {
                return is_time();
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