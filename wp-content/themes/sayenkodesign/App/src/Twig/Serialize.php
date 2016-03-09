<?php
namespace App\Twig;

use Twig_Extension;

class Serialize extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('maybe_serialize', function($data){
                maybe_serialize( $data );
            }),

            new \Twig_SimpleFilter('maybe_unserialize', function($original){
                maybe_unserialize( $original );
            }),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_serialized', function ($data) {
                return is_serialized( $data );
            }),

            new \Twig_SimpleFunction('is_serialized_string', function ($data) {
                return is_serialized_string( $data );
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