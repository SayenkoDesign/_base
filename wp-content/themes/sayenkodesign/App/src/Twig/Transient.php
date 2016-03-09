<?php
namespace App\Twig;

use Twig_Extension;

class Transient extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_transient', function ($transient) {
                return get_transient( $transient );
            }),

            new \Twig_SimpleFunction('get_transient', function ($transient) {
                return get_transient( $transient );
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