<?php
namespace App\Twig;

use Twig_Extension;

class User extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('author_can', function ($post, $capability) {
                return author_can( $post, $capability );
            }),

            new \Twig_SimpleFunction('current_user_can', function ($capability) {
                return current_user_can( $capability );
            }),

            new \Twig_SimpleFunction('current_user_can_for_blog', function ($blog_id, $capability) {
                return current_user_can_for_blog($blog_id, $capability);
            }),

            new \Twig_SimpleFunction('get_role', function ($role) {
                return get_role( $role );
            }),

            new \Twig_SimpleFunction('get_super_admins', function () {
                return get_super_admins();
            }),

            new \Twig_SimpleFunction('is_super_admin', function ($user_id = false) {
                return is_super_admin( $user_id );
            }),

            new \Twig_SimpleFunction('user_can', function ($user, $capability) {
                return user_can( $user, $capability );
            }),

            new \Twig_SimpleFunction('user_can', function ($strategy = 'time') {
                return count_users( $strategy );
            }),

            new \Twig_SimpleFunction('count_user_posts', function ($userid, $post_type = 'post', $public_only = false) {
                return count_user_posts( $userid , $post_type );
            }),

            new \Twig_SimpleFunction('count_many_users_posts', function ($users, $post_type = 'post', $public_only = false) {
                return count_many_users_posts( $users, $post_type, $public_only );
            }),

            new \Twig_SimpleFunction('email_exists', function ($email) {
                return email_exists( $email );
            }),

            new \Twig_SimpleFunction('get_currentuserinfo', function () {
                return get_currentuserinfo();
            }),

            new \Twig_SimpleFunction('get_current_user_id', function () {
                return get_current_user_id();
            }),

            new \Twig_SimpleFunction('get_user_by', function ($field, $value) {
                return get_user_by($field, $value);
            }),

            new \Twig_SimpleFunction('get_userdata', function ($user_id) {
                return get_userdata( $user_id );
            }),

            new \Twig_SimpleFunction('get_users', function ($args = array()) {
                return get_users( $args );
            }),

            new \Twig_SimpleFunction('username_exists', function ($username) {
                return username_exists( $username );
            }),

            new \Twig_SimpleFunction('validate_username', function ($username) {
                return validate_username( $username );
            }),

            new \Twig_SimpleFunction('wp_get_current_user', function () {
                return wp_get_current_user();
            }),

            new \Twig_SimpleFunction('get_author_posts_url', function ($author_id, $author_nicename = '') {
                return get_author_posts_url( $author_id, $author_nicename );
            }),

            new \Twig_SimpleFunction('get_the_modified_author', function () {
                return get_the_modified_author();
            }),

            new \Twig_SimpleFunction('is_multi_author', function () {
                return is_multi_author();
            }),

            new \Twig_SimpleFunction('is_multi_author', function ($user_id, $key = '', $single = false) {
                return get_user_meta($user_id, $key, $single);
            }),

            new \Twig_SimpleFunction('get_the_author_meta', function ($field = '', $user_id = false) {
                return get_the_author_meta($field, $user_id);
            }),

            new \Twig_SimpleFunction('is_user_logged_in', function () {
                return is_user_logged_in();
            }),

            new \Twig_SimpleFunction('is_user_logged_in', function ($args = array()) {
                return wp_login_form( $args );
            }),

            new \Twig_SimpleFunction('wp_loginout', function ($redirect = '') {
                return wp_loginout( $redirect, false );
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