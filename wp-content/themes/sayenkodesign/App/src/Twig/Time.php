<?php
namespace App\Twig;

use Twig_Extension;

class Time extends Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('current_time', function ($type, $gmt = 0) {
                return current_time( $type, $gmt );
            }),

            new \Twig_SimpleFunction('date_i18n', function ($dateformatstring, $unixtimestamp = false, $gmt = false) {
                return date_i18n( $dateformatstring, $unixtimestamp, $gmt );
            }),

            new \Twig_SimpleFunction('get_calendar', function ($initial) {
                return get_calendar( $initial, false );
            }),

            new \Twig_SimpleFunction('get_date_from_gmt', function ($string, $format = 'Y-m-d H:i:s') {
                return get_date_from_gmt( $string, $format );
            }),

            new \Twig_SimpleFunction('get_lastpostdate', function ($timezone) {
                return get_lastpostdate( $timezone );
            }),

            new \Twig_SimpleFunction('get_lastpostmodified', function ($timezone) {
                return get_lastpostmodified( $timezone );
            }),

            new \Twig_SimpleFunction('get_day_link', function ($year, $month, $day) {
                return get_day_link( $year, $month, $day );
            }),

            new \Twig_SimpleFunction('get_gmt_from_date', function ($string) {
                return get_gmt_from_date( $string );
            }),

            new \Twig_SimpleFunction('get_gmt_from_date', function ($string) {
                return get_gmt_from_date( $string );
            }),

            new \Twig_SimpleFunction('get_month_link', function ($year, $month) {
                return get_month_link( $year, $month );
            }),

            new \Twig_SimpleFunction('get_the_date', function ($format, $post_id) {
                return get_the_date( $format, $post_id );
            }),

            new \Twig_SimpleFunction('get_the_time', function ($format, $post) {
                return get_the_time( $format, $post );
            }),

            new \Twig_SimpleFunction('get_the_modified_time', function ($d) {
                return get_the_modified_time( $d );
            }),

            new \Twig_SimpleFunction('get_weekstartend', function ($mysqlstring, $start_of_week) {
                return get_weekstartend( $mysqlstring, $start_of_week );
            }),

            new \Twig_SimpleFunction('get_year_link', function ($year) {
                return get_year_link( $year );
            }),

            new \Twig_SimpleFunction('get_year_link', function ($year) {
                return get_year_link( $year );
            }),

            new \Twig_SimpleFunction('human_time_diff', function ($from, $to) {
                return human_time_diff( $from, $to );
            }),

            new \Twig_SimpleFunction('is_new_day', function () {
                return is_new_day();
            }),

            new \Twig_SimpleFunction('is_new_day', function ($timezone) {
                return iso8601_timezone_to_offset( $timezone );
            }),

            new \Twig_SimpleFunction('is_new_day', function ($date_string, $timezone = 'user') {
                return iso8601_to_datetime( $date_string, $timezone );
            }),

            new \Twig_SimpleFunction('mysql2date', function ($format, $date, $translate = true) {
                return mysql2date( $format, $date, $translate );
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