<?php
/**
 * Plugin Name: URI RSS Reader
 * Plugin URI: http://www.uri.edu
 * Description: A RSS Reader for news feeds
 * Version: 1.2.3
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

define( 'URI_RSS_READER_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Include css and js
 */
function uri_rss_reader_enqueues() {

	wp_register_style( 'uri-rss-reader-css', plugins_url( '/css/style.built.css', __FILE__ ), array(), uri_rss_reader_cache_buster(), 'all' );
	wp_enqueue_style( 'uri-rss-reader-css' );

	wp_register_script( 'uri-rss-reader-js', plugins_url( '/js/script.built.js', __FILE__ ), array(), uri_rss_reader_cache_buster(), true );
	wp_enqueue_script( 'uri-rss-reader-js' );

}
add_action( 'wp_enqueue_scripts', 'uri_rss_reader_enqueues' );

/**
 * Returns version from package.json to be used for cache busting
 *
 * @return str
 */
function uri_rss_reader_cache_buster() {
	static $cache_buster;
	if ( empty( $cache_buster ) && function_exists( 'get_plugin_data' ) ) {
		$values = get_plugin_data( URI_RSS_READER_DIR_PATH . 'uri-rss-reader.php', false );
		$cache_buster = $values['Version'];
	} else {
		$cache_buster = gmdate( 'Ymd', strtotime( 'now' ) );
	}
	return $cache_buster;
}

// Include shortcodes
include( URI_RSS_READER_DIR_PATH . 'inc/uri-rss-reader-shortcode.php' );

// Include parsing
include( URI_RSS_READER_DIR_PATH . 'inc/uri-rss-reader-parse.php' );

// Include caching
include( URI_RSS_READER_DIR_PATH . 'inc/uri-rss-reader-cache.php' );
