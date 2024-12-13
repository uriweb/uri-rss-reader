<?php

/**
 * URI RSS READER SHORTCODE
 *
 * @package uri-rss-reader
 */

/**
 * Create a shortcode for displaying rss news feeds
 */
function uri_rss_reader_shortcode($attributes, $shortcode)
{

    // default attributes
    $attributes = shortcode_atts(array(
        'url' => NULL,
        'exclude' => NULL,
        'before' => '<div class="uri-rss-reader">',
        'after' => '</div>'
    ), $attributes, $shortcode);

    $exclude_urls = explode(", ", $attributes['exclude']);
    $feed_data = uri_rss_reader_load_xml( $attributes['url']);
    
    
    $output = uri_rss_reader_feed_list($feed_data, $exclude_urls);
    

    return $output;
}

add_shortcode('uri-rss-reader', 'uri_rss_reader_shortcode');

function uri_rss_reader_2_shortcode($attributes, $shortcode) {
    // default attributes
    $attributes = shortcode_atts(array(
        'url' => NULL,
        'exclude' => NULL,
        'before' => '<div class="uri-rss-reader">',
        'after' => '</div>'
    ), $attributes, $shortcode);

    $exclude_urls = explode(", ", $attributes['exclude']);
$xml_data = uri_rss_reader_get_xml($attributes['url']);
$feed_data = uri_rss_reader_get_array($xml_data, $exclude_urls);
$output = uri_rss_reader_display ($feed_data, $attributes);
return $output;

}

add_shortcode('uri-rss-reader2', 'uri_rss_reader_2_shortcode');
