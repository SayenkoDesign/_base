<?php
namespace App\Twig;

use Twig_Extension;

class Attachment extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_attached_file', function ($attachment_id, $unfiltered = false) {
                return get_attached_file( $attachment_id, $unfiltered );
            }),

            new \Twig_SimpleFunction('is_attachment', function ($attachment = '') {
                return is_attachment($attachment);
            }),

            new \Twig_SimpleFunction('is_local_attachment', function ($url) {
                return is_local_attachment( $url );
            }),

            new \Twig_SimpleFunction('wp_attachment_is_image', function ($post = 0) {
                return wp_attachment_is_image( $post );
            }),

            new \Twig_SimpleFunction('wp_attachment_is_image', function ($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {
                return wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_link', function ($id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false, $attr = '') {
                return wp_get_attachment_link( $id, $size, $permalink, $icon, $text );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_image_src', function ($attachment_id, $size = 'thumbnail', $icon = false) {
                return wp_get_attachment_image_src( $attachment_id, $size, $icon );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_metadata', function ($post_id = 0, $unfiltered = false) {
                return wp_get_attachment_metadata( $post_id, $unfiltered );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_thumb_file', function ($post_id = 0) {
                return wp_get_attachment_thumb_file( $post_id );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_thumb_url', function ($post_id = 0) {
                return wp_get_attachment_thumb_url( $post_id );
            }),

            new \Twig_SimpleFunction('wp_get_attachment_url', function ($post_id = 0) {
                return wp_get_attachment_url( $post_id );
            }),

            new \Twig_SimpleFunction('wp_check_for_changed_slugs', function ($post_id, $post, $post_before) {
                wp_check_for_changed_slugs( $post_id, $post, $post_before );
                return true;
            }),

            new \Twig_SimpleFunction('wp_count_posts', function ($type = 'post', $perm = '') {
                return wp_count_posts( $type, $perm );
            }),

            new \Twig_SimpleFunction('wp_get_mime_types', function () {
                return wp_get_mime_types();
            }),

            new \Twig_SimpleFunction('wp_mime_type_icon', function ($mime = 0) {
                return wp_mime_type_icon( $mime );
            }),

            new \Twig_SimpleFunction('wp_generate_attachment_metadata', function ($attachment_id, $file) {
                return wp_generate_attachment_metadata( $attachment_id, $file );
            }),

            new \Twig_SimpleFunction('wp_prepare_attachment_for_js', function ($attachment) {
                return wp_prepare_attachment_for_js( $attachment );
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