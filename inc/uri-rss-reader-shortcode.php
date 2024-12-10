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

    $feed_data = uri_rss_reader_load_xml( $attributes['url']);
    $exclude_urls = explode(", ", $attributes['exclude']);
    $output = uri_rss_reader_feed_list($feed_data, $exclude_urls);

    return $output;
}

add_shortcode('uri-rss-reader', 'uri_rss_reader_shortcode');
