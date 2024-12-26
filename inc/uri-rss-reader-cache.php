<?php

/**
 * URI RSS READER
 *
 * @package uri-rss-reader
 */

/**
 * check if a date has recency
 *
 * @param  int time
 * @return bool
 */
function uri_rss_reader_cache_is_expired($time)
{
	$recency = get_site_option( 'uri_rss_reader_recency', '1 hour' );
	$expiry = strtotime( '-' . $recency, strtotime('now') );
var_dump($recency);
var_dump($expiry);
var_dump($time);
	return ( $time < $expiry );
	
}

/**
 * hash a string; currently md5, someday something else.
 * @param str $string the string to hash
 * @return str
 */
function uri_rss_reader_hash_string ( $string ) {
	$hash = md5( $string );
	return $hash;
  }

/**
 * Save the data retrieved from the feed as a WordPress option
 */
function uri_rss_reader_cache_update($url, $feed_data)
{

	$hash = uri_rss_reader_hash_string( $url );

	$cache = array();
	$cache['time'] = strtotime('now');
	$cache['res'] = $feed_data;
  
	$data = get_option( 'uri_rss_reader_cache' );
	   if ( empty ( $data ) ) {
	  $data = array();
	}
  
	$data[$hash] = $cache;
	//var_dump($cache);
	update_option( 'uri_rss_reader_cache', $data, TRUE );
	echo '<br/> cache updated for ' . $hash;
  
	//var_dump($data);

}


function uri_rss_reader_cache_retrieve($url)
{
	$data = get_option( 'uri_rss_reader_cache' );
	$hash = uri_rss_reader_hash_string( $url );
  
	//var_dump($data);
  
	if (is_array( $data ) &&  array_key_exists( $hash, $data ) ) {
  
	  echo '<br />cache exists for ' . $hash;
	  $cache = $data[$hash];

	  if ( uri_rss_reader_cache_is_expired( $cache['time'] ) ) {
		echo '<br />cache is expired for ' . $hash;
		return false;
	  }
  
	  echo '<br />using cache for ' . $hash;
	  return $cache['res'];
  
	}
  
	echo '<br />no cache exists for ' . $hash;
	return false;

}


/**
 * Get data from either the cache or directly from the url, depending on what's fresher
 *
 * @param str $url the url
 */
function uri_rss_reader_get_the_feed($url)
{


	$cache = uri_rss_reader_cache_retrieve($url);

	// if we have a good cache, use it
	if ($cache) {
		echo '<br />using cache for ' . uri_rss_reader_hash_string( $url );
		return $cache;
	}

	// otherwise, get the xml data from the url...
	$rss = uri_rss_reader_get_xml($url);

	// build the feed from the xml items we want [title, link, thumbnail image, alt-text]...
	$feed_data = uri_rss_reader_build_array($rss);

	// cache the feed data...
	uri_rss_reader_cache_update($url, $feed_data);

	// and return the feed data.
	return $feed_data;
}
