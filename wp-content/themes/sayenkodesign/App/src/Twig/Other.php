<?php
namespace App\Twig;

use Twig_Extension;

class Other extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_the_ID', function () {
                return get_the_ID();
            }),

            new \Twig_SimpleFunction('get_the_author', function () {
                return get_the_author();
            }),

            new \Twig_SimpleFunction('get_the_author_posts', function () {
                return get_the_author_posts();
            }),

            new \Twig_SimpleFunction('get_the_content', function ($more_link_text = null, $strip_teaser = false) {
                return get_the_content( $more_link_text, $strip_teaser );
            }),

            new \Twig_SimpleFunction('get_the_title', function ($post = 0) {
                return get_the_title( $post );
            }),

            new \Twig_SimpleFunction('wp_get_post_revision', function (&$post, $output = OBJECT, $filter = 'raw') {
                return wp_get_post_revision( $post, $output, $filter );
            }),

            new \Twig_SimpleFunction('wp_get_post_revisions', function ($post_id = 0, $args = null) {
                return wp_get_post_revisions( $post_id, $args );
            }),

            new \Twig_SimpleFunction('wp_is_post_revision', function ($post) {
                return wp_is_post_revision( $post );
            }),

            new \Twig_SimpleFunction('paginate_links', function ($args = '') {
                return paginate_links( $args );
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