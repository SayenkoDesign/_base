<?php
namespace App\Twig;

use Twig_Extension;

class Widget extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_active_widget', function ($callback = false, $widget_id = false, $id_base = false, $skip_inactive = true) {
                return is_active_widget( $callback, $widget_id, $id_base, $skip_inactive );
            }),

            new \Twig_SimpleFunction('is_active_widget', function ($callback = false, $widget_id = false, $id_base = false, $skip_inactive = true) {
                return is_active_widget( $callback, $widget_id, $id_base, $skip_inactive );
            }),

            new \Twig_SimpleFunction('the_widget', function ($widget, $instance = array(), $args = array()) {
                ob_start();
                the_widget( $widget, $instance, $args );
                return ob_get_clean();
            }),

            new \Twig_SimpleFunction('is_active_widget', function ($callback = false, $widget_id = false, $id_base = false, $skip_inactive = true) {
                return is_active_widget( $callback, $widget_id, $id_base, $skip_inactive );
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