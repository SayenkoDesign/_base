<?php
namespace App\Twig;

use Twig_Extension;

class Feed extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_author_feed_link', function ($author_id, $feed = '') {
                return get_author_feed_link( $author_id, $feed );
            }),

            new \Twig_SimpleFunction('get_bloginfo_rss', function ($show = '') {
                return get_bloginfo_rss( $show );
            }),

            new \Twig_SimpleFunction('get_category_feed_link', function ($cat_id, $feed = '') {
                return get_category_feed_link( $cat_id, $feed );
            }),

            new \Twig_SimpleFunction('get_comment_link', function ($comment = null, $args = array()) {
                return get_comment_link( $comment, $args );
            }),

            new \Twig_SimpleFunction('get_comment_author_rss', function () {
                return get_comment_author_rss();
            }),

            new \Twig_SimpleFunction('get_post_comments_feed_link', function ($post_id = 0, $feed = '') {
                return get_post_comments_feed_link( $post_id, $feed );
            }),

            new \Twig_SimpleFunction('get_search_comments_feed_link', function ($search_query = '', $feed = '') {
                return get_search_comments_feed_link( $search_query, $feed );
            }),

            new \Twig_SimpleFunction('get_search_feed_link', function ($search_query = '', $feed = '') {
                return get_search_feed_link( $search_query, $feed );
            }),

            new \Twig_SimpleFunction('get_the_category_rss', function ($type = null) {
                return get_the_category_rss( $type );
            }),

            new \Twig_SimpleFunction('get_the_title_rss', function () {
                return get_the_title_rss();
            })
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