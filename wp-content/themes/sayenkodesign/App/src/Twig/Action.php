<?php
namespace App\Twig;

use Twig_Extension;

class Action extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('has_action', function ($tag, $function_to_check = false) {
                return has_action( $tag, $function_to_check );
            }),

            new \Twig_SimpleFunction('do_action', function ($tag, $arg = '') {
                do_action( $tag, $arg );
                return true;
            }),

            new \Twig_SimpleFunction('do_action_ref_array', function ($tag, $args) {
                do_action_ref_array( $tag, $args );
                return true;
            }),

            new \Twig_SimpleFunction('did_action', function ($tag) {
                return did_action( $tag );
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