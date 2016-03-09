<?php
namespace App\Twig;

use Twig_Extension;

class Filter extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('apply_filters', function ($tag, $value) {
                return apply_filters($tag, $value);
            }),

            new \Twig_SimpleFunction('apply_filters_ref_array', function ($tag, $args) {
                return apply_filters_ref_array( $tag, $args );
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