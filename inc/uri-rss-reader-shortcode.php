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
        'before' => '<div class="uri-rss-reader">',
        'after' => '</div>'
    ), $attributes, $shortcode);

    $output = $attributes['url'];

    return $output;
}

add_shortcode('uri-rss', 'uri_rss_reader_shortcode');
