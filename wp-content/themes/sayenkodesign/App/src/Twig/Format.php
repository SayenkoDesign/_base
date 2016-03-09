<?php
namespace App\Twig;

use Twig_Extension;

class Format extends Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('absint', function($maybeint){
                return absint( $maybeint );
            }),

            new \Twig_SimpleFilter('add_magic_quotes', function($array){
                return add_magic_quotes( $array );
            }),

            new \Twig_SimpleFilter('addslashes_gpc', function($gpc){
                return addslashes_gpc( $gpc );
            }),

            new \Twig_SimpleFilter('antispambot', function($emailaddy, $hex_encoding = 0){
                return antispambot( $emailaddy, $hex_encoding );
            }),

            new \Twig_SimpleFilter('backslashit', function($string){
                return backslashit( $string );
            }),

            new \Twig_SimpleFilter('balanceTags', function($text, $force = false){
                return balanceTags( $text, $force );
            }),

            new \Twig_SimpleFilter('convert_chars', function($content, $deprecated = ''){
                return convert_chars( $content, $deprecated );
            }),

            new \Twig_SimpleFilter('convert_smilies', function($text){
                return convert_smilies( $text );
            }),

            new \Twig_SimpleFilter('ent2ncr', function($text){
                return ent2ncr( $text );
            }),

            new \Twig_SimpleFilter('esc_attr', function($text){
                return esc_attr( $text );
            }),

            new \Twig_SimpleFilter('esc_html', function($text){
                return esc_html( $text );
            }),

            new \Twig_SimpleFilter('esc_js', function($text){
                return esc_js( $text );
            }),

            new \Twig_SimpleFilter('esc_textarea', function($text){
                return esc_textarea( $text );
            }),

            new \Twig_SimpleFilter('esc_sql', function($sql){
                return esc_sql( $sql );
            }),

            new \Twig_SimpleFilter('esc_url', function($url, $protocols = null, $_context = 'display'){
                return esc_url( $url, $protocols, $_context );
            }),

            new \Twig_SimpleFilter('esc_url_raw', function($url, $protocols){
                return esc_url_raw( $url, $protocols );
            }),

            new \Twig_SimpleFilter('format_to_edit', function($content, $rich_text = false){
                return format_to_edit( $content, $rich_text );
            }),

            new \Twig_SimpleFilter('htmlentities2', function($myHTML){
                return htmlentities2( $myHTML );
            }),

            new \Twig_SimpleFilter('make_clickable', function($text){
                return make_clickable( $text );
            }),

            new \Twig_SimpleFilter('popuplinks', function($text){
                return popuplinks( $text );
            }),

            new \Twig_SimpleFilter('remove_accents', function($string){
                return remove_accents( $string );
            }),

            new \Twig_SimpleFilter('sanitize_email', function($email){
                return sanitize_email( $email );
            }),

            new \Twig_SimpleFilter('sanitize_file_name', function($name){
                return sanitize_file_name( $name );
            }),

            new \Twig_SimpleFilter('sanitize_html_class', function($class, $fallback = ''){
                return sanitize_html_class( $class, $fallback );
            }),

            new \Twig_SimpleFilter('sanitize_key', function($key){
                return sanitize_key( $key );
            }),

            new \Twig_SimpleFilter('sanitize_mime_type', function($mime_type){
                return sanitize_mime_type( $mime_type );
            }),

            new \Twig_SimpleFilter('sanitize_option', function($option, $value){
                return sanitize_option( $option, $value );
            }),

            new \Twig_SimpleFilter('sanitize_sql_orderby', function($orderby){
                return sanitize_sql_orderby( $orderby );
            }),

            new \Twig_SimpleFilter('sanitize_text_field', function($str){
                return sanitize_text_field( $str ) ;
            }),

            new \Twig_SimpleFilter('sanitize_text_field', function($title, $fallback_title = '', $context = 'save'){
                return sanitize_title( $title, $fallback_title, $context );
            }),

            new \Twig_SimpleFilter('sanitize_title_for_query', function($title){
                return sanitize_title_for_query( $title );
            }),

            new \Twig_SimpleFilter('sanitize_title_with_dashes', function($title, $raw_title = '', $context = 'display'){
                return sanitize_title_with_dashes( $title, $raw_title, $context );
            }),

            new \Twig_SimpleFilter('sanitize_user', function($username, $strict = false){
                return sanitize_user( $username, $strict );
            }),

            new \Twig_SimpleFilter('sanitize_title_with_dashes', function($str){
                return seems_utf8( $str );
            }),

            new \Twig_SimpleFilter('stripslashes_deep', function($value){
                return stripslashes_deep( $value );
            }),

            new \Twig_SimpleFilter('trailingslashit', function($string){
                return trailingslashit( $string );
            }),

            new \Twig_SimpleFilter('untrailingslashit', function($string){
                return untrailingslashit( $string );
            }),

            new \Twig_SimpleFilter('urlencode_deep', function($value){
                return urlencode_deep( $value );
            }),

            new \Twig_SimpleFilter('url_shorten', function($url, $length = 35){
                return url_shorten($url, $length);
            }),

            new \Twig_SimpleFilter('url_shorten', function($utf8_string, $length = 0){
                return utf8_uri_encode( $utf8_string, $length );
            }),

            new \Twig_SimpleFilter('wpautop', function($pee, $br = true){
                return wpautop( $pee, $br );
            }),

            new \Twig_SimpleFilter('wp_filter_kses', function($data){
                return wp_filter_kses( $data );
            }),

            new \Twig_SimpleFilter('wp_filter_post_kses', function($data){
                return wp_filter_post_kses( $data );
            }),

            new \Twig_SimpleFilter('wp_filter_nohtml_kses', function($data){
                return wp_filter_nohtml_kses( $data );
            }),

            new \Twig_SimpleFilter('wp_iso_descrambler', function($string){
                return wp_iso_descrambler( $string );
            }),

            new \Twig_SimpleFilter('wp_kses', function($string, $allowed_html, $allowed_protocols = array()){
                return wp_kses($string, $allowed_html, $allowed_protocols);
            }),

            new \Twig_SimpleFilter('wp_kses', function($string, $allowed_html, $allowed_protocols = array()){
                return wp_kses($string, $allowed_html, $allowed_protocols);
            }),

            new \Twig_SimpleFilter('wp_kses_array_lc', function($inarray){
                return wp_kses_array_lc( $inarray );
            }),

            new \Twig_SimpleFilter('wp_kses_attr', function($element, $attr, $allowed_html, $allowed_protocols){
                return wp_kses_attr( $element, $attr, $allowed_html, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_attr', function($element, $attr, $allowed_html, $allowed_protocols){
                return wp_kses_attr( $element, $attr, $allowed_html, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_bad_protocol', function($string, $allowed_protocols){
                return wp_kses_bad_protocol( $string, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_bad_protocol_once', function($string, $allowed_protocols, $count = 1){
                return wp_kses_bad_protocol_once( $string, $allowed_protocols, $count );
            }),

            new \Twig_SimpleFilter('wp_kses_bad_protocol_once2', function($string, $allowed_protocols){
                return wp_kses_bad_protocol_once2( $string, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_check_attr_val', function($value, $vless, $checkname, $checkvalue){
                return wp_kses_check_attr_val( $value, $vless, $checkname, $checkvalue );
            }),

            new \Twig_SimpleFilter('wp_kses_decode_entities', function($string){
                return wp_kses_decode_entities( $string );
            }),

            new \Twig_SimpleFilter('wp_kses_hair', function($attr, $allowed_protocols){
                return wp_kses_hair( $attr, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_hook', function($string, $allowed_html, $allowed_protocols){
                return wp_kses_hook( $string, $allowed_html, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_html_error', function($string){
                return wp_kses_html_error( $string );
            }),

            new \Twig_SimpleFilter('wp_kses_html_error', function($string){
                return wp_kses_js_entities( $string );
            }),

            new \Twig_SimpleFilter('wp_kses_no_null', function($string){
                return wp_kses_no_null( $string );
            }),

            new \Twig_SimpleFilter('wp_kses_normalize_entities', function($string){
                return wp_kses_normalize_entities( $string );
            }),

            new \Twig_SimpleFilter('wp_kses_normalize_entities2', function($matches){
                return wp_kses_normalize_entities2( $matches );
            }),

            new \Twig_SimpleFilter('wp_kses_normalize_entities2', function($matches){
                return wp_kses_normalize_entities2( $matches );
            }),

            new \Twig_SimpleFilter('wp_kses_split', function($string, $allowed_html, $allowed_protocols){
                return wp_kses_split( $string, $allowed_html, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_split2', function($string, $allowed_html, $allowed_protocols){
                return wp_kses_split2( $string, $allowed_html, $allowed_protocols );
            }),

            new \Twig_SimpleFilter('wp_kses_split2', function($string){
                return wp_kses_stripslashes( $string );
            }),

            new \Twig_SimpleFilter('wp_make_link_relative', function($link){
                return wp_make_link_relative( $link );
            }),

            new \Twig_SimpleFilter('wp_normalize_path', function($path){
                return wp_normalize_path($path);
            }),

            new \Twig_SimpleFilter('wp_rel_nofollow', function($text){
                return wp_rel_nofollow( $text );
            }),

            new \Twig_SimpleFilter('wp_rel_nofollow', function($text, $num_words = 55, $more = null){
                return wp_trim_words( $text, $num_words, $more );
            }),

            new \Twig_SimpleFilter('zeroise', function($number, $threshold){
                return zeroise( $number, $threshold );
            }),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_email', function($email){
                return is_email( $email );
            }),

            new \Twig_SimpleFunction('wp_kses_version', function(){
                return wp_kses_version();
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