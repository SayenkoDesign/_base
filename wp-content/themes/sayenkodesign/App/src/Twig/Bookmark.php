<?php
namespace App\Twig;

use Twig_Extension;

class Bookmark extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_bookmark', function ($bookmark, $output = OBJECT, $filter = 'raw') {
                return get_bookmark( $bookmark, $output, $filter );
            }),

            new \Twig_SimpleFunction('get_bookmarks', function ($args = '') {
                return get_bookmarks( $args );
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