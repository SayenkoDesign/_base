<?php
namespace App\Twig;

use Twig_Extension;

class Tag extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_tag', function ($tag, $output = OBJECT, $filter = 'raw') {
                return get_tag( $tag, $output, $filter );
            }),

            new \Twig_SimpleFunction('get_tag_link', function ($tag) {
                return get_tag_link($tag);
            }),

            new \Twig_SimpleFunction('get_tags', function ($args = '') {
                return get_tags( $args );
            }),

            new \Twig_SimpleFunction('get_the_tag_list', function ($before = '', $sep = '', $after = '', $id = 0) {
                return get_the_tag_list( $before, $sep, $after );
            }),

            new \Twig_SimpleFunction('get_the_tags', function ($id = 0) {
                return get_the_tags($id);
            }),

            new \Twig_SimpleFunction('has_term', function ($tag = '', $post = null) {
                return has_term($tag, $post);
            }),

            new \Twig_SimpleFunction('is_tag', function ($tag = '') {
                return is_tag( $tag );
            }),

            new \Twig_SimpleFunction('single_tag_title', function ($prefix = '', $display = true) {
                return single_tag_title( $prefix, $display );
            }),

            new \Twig_SimpleFunction('tag_description', function ($tag = 0) {
                return tag_description( $tag );
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