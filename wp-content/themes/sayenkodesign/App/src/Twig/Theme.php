<?php
namespace App\Twig;

use Twig_Extension;

class Theme extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('comments_template', function ($file = '/comments.php', $separate_comments = false) {
                ob_start();
                comments_template($file, $separate_comments);
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('get_footer', function ($name = null) {
                ob_start();
                get_footer( $name );
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('get_header', function ($name = null) {
                ob_start();
                get_header( $name );
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('get_sidebar', function ($name = null) {
                ob_start();
                get_sidebar( $name );
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('get_search_form', function () {
                return get_search_form( false );
            }),

            new \Twig_SimpleFunction('get_body_class', function ($class = '') {
                return get_body_class($class);
            }),

            new \Twig_SimpleFunction('get_search_form', function ($feature) {
                return current_theme_supports( $feature );
            }),

            new \Twig_SimpleFunction('dynamic_sidebar', function ($index = 1) {
                return dynamic_sidebar( $index );
            }),

            new \Twig_SimpleFunction('get_header_image', function () {
                return get_header_image();
            }),

            new \Twig_SimpleFunction('get_header_textcolor', function () {
                return get_header_textcolor();
            }),

            new \Twig_SimpleFunction('get_post_class', function ($class = '', $post_id = null) {
                return get_post_class($class, $post_id);
            }),

            new \Twig_SimpleFunction('get_stylesheet_directory_uri', function () {
                return get_stylesheet_directory_uri();
            }),

            new \Twig_SimpleFunction('get_stylesheet_uri', function () {
                return get_stylesheet_uri();
            }),

            new \Twig_SimpleFunction('get_template_directory_uri', function () {
                return get_template_directory_uri();
            }),

            new \Twig_SimpleFunction('get_template_part', function ($slug, $name = null) {
                ob_start();
                get_template_part($slug, $name);
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('wp_get_themes', function ($args = array()) {
                return wp_get_themes( $args );
            }),

            new \Twig_SimpleFunction('get_theme_support', function ($feature) {
                return get_theme_support( $feature );
            }),

            new \Twig_SimpleFunction('get_theme_mod', function ($name, $default = false) {
                return get_theme_mod( $name, $default );
            }),

            new \Twig_SimpleFunction('get_theme_mods', function () {
                return get_theme_mods();
            }),

            new \Twig_SimpleFunction('get_theme_root', function ($stylesheet_or_template = false) {
                return get_theme_root( $stylesheet_or_template );
            }),

            new \Twig_SimpleFunction('get_theme_roots', function () {
                return get_theme_roots();
            }),

            new \Twig_SimpleFunction('get_theme_root_uri', function () {
                return get_theme_root_uri();
            }),

            new \Twig_SimpleFunction('has_header_image', function () {
                return has_header_image();
            }),

            new \Twig_SimpleFunction('is_child_theme', function () {
                return is_child_theme();
            }),

            new \Twig_SimpleFunction('is_active_sidebar', function ($index) {
                return is_active_sidebar( $index );
            }),

            new \Twig_SimpleFunction('is_admin_bar_showing', function () {
                return is_admin_bar_showing();
            }),

            new \Twig_SimpleFunction('is_customize_preview', function () {
                return is_customize_preview();
            }),

            new \Twig_SimpleFunction('is_dynamic_sidebar', function () {
                return is_dynamic_sidebar();
            }),

            new \Twig_SimpleFunction('language_attributes', function ($doctype = 'html') {
                ob_start();
                language_attributes( $doctype );
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('load_template', function ($_template_file, $require_once = true) {
                ob_start();
                load_template( $_template_file, $require_once );
                return ob_end_clean();
            }),

            new \Twig_SimpleFunction('get_registered_nav_menus', function () {
                return get_registered_nav_menus();
            }),

            new \Twig_SimpleFunction('wp_get_archives', function ($args = '') {
                return wp_get_archives( $args );
            }),

            new \Twig_SimpleFunction('wp_get_nav_menu_items', function ($menu, $args = array()) {
                return wp_get_nav_menu_items($menu, $args);
            }),

            new \Twig_SimpleFunction('wp_get_theme', function ($stylesheet = null, $theme_root = null) {
                return wp_get_theme( $stylesheet, $theme_root );
            }),

            new \Twig_SimpleFunction('wp_get_theme', function ($args = array()) {
                return wp_nav_menu( $args );
            }),

            new \Twig_SimpleFunction('wp_page_menu', function ($args = array()) {
                return wp_page_menu( $args );
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