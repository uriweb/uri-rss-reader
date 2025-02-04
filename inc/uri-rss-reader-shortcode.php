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
    $attributes = shortcode_atts(
        array(
            'url' => NULL,
            'display' => '20',
            'exclude' => NULL,
            'include_excerpt' => 'true',
            'include_date' => 'true',
            'include_image' => 'true', 
            'cache' => '1 hour',
            'before' => '<div class="uri-rss-reader">',
            'after' => '</div>'
        ),
        $attributes,
        $shortcode
    );

    // Turn excluded urls into an array
    $exclude_urls = explode(", ", $attributes['exclude']);

    // save cache time to live as site option
    update_site_option('uri_rss_reader_recency', $attributes['cache']);

    //Get raw xml data
    //$xml_data = uri_rss_reader_get_xml($attributes['url']);

    //Get array from raw xml data
    //$feed_data = uri_rss_reader_build_array($xml_data, $attributes['cache']);

    //go straight to cache check instead 
    $feed_data = uri_rss_reader_get_the_feed($attributes['url']);

    //Display the feed
    $output = uri_rss_reader_display($feed_data, $attributes, $exclude_urls);


    return $output;
}

add_shortcode('uri-rss-reader', 'uri_rss_reader_shortcode');
