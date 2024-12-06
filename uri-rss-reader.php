<?php
/**
 * Plugin Name: URI RSS Reader
 * Plugin URI: http://www.uri.edu
 * Description: A RSS Reader for news feeds
 * Version: 0.1.0
 * Author: URI Web Communications
 * Author URI: https://today.uri.edu/
 *
 * @author: Alexandra Gauss <alexandra_gauss@uri.edu>
 * @package uri-rss-reader
 */

// Block direct requests
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Include css and js
 */
function uri_rss_reader_enqueues() {

	wp_register_style( 'uri-rss-reader-css', plugins_url( '/css/style.built.css', __FILE__ ) );
	wp_enqueue_style( 'uri-rss-reader-css' );

	wp_register_script( 'uri-rss-reader-js', plugins_url( '/js/script.built.js', __FILE__ ) );
	wp_enqueue_script( 'uri-rss-reader-js' );

}
add_action( 'wp_enqueue_scripts', 'uri_rss_reader_enqueues' );
