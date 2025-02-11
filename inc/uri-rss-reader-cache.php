<?php

/**
 * URI RSS READER
 *
 * @package uri-rss-reader
 */

/**
 * Check if a date has recency
 *
 * @param  int time
 * 
 * @return bool
 */
function uri_rss_reader_cache_is_expired($time)
{
	$recency = get_site_option('uri_rss_reader_recency', '1 hour');
	$expiry = strtotime('-' . $recency, strtotime('now'));

	return ($time < $expiry);
}

/**
 * Hash a string.
 * @param str $string the string to hash
 * 
 * @return str
 */
function uri_rss_reader_hash_string($string)
{
	$hash = md5($string);
	return $hash;
}

/**
 * Save the data retrieved from the feed as a WordPress option
 * @param str $url the url
 * @param arr $feed_data the data we want for the feed
 */
function uri_rss_reader_cache_update($url, $feed_data)
{

	$hash = uri_rss_reader_hash_string($url);

	$cache = array();
	$cache['time'] = strtotime('now');
	$cache['res'] = $feed_data;

	$data = get_option('uri_rss_reader_cache');
	if (empty($data)) {
		$data = array();
	}

	$data[$hash] = $cache;
	//var_dump($cache);
	update_option('uri_rss_reader_cache', $data, TRUE);
	//echo '<br/> cache updated for ' . $hash;

	//var_dump($data);

}

/**
 * Retrieve data from the save Wordpress option cache 
 * @param str $url of the url
 * 
 * @return FALSE if no cache or cache is expired
 * @return cached data if it exists and isn't expired
 */
function uri_rss_reader_cache_retrieve($url)
{
	$data = get_option('uri_rss_reader_cache');
	$hash = uri_rss_reader_hash_string($url);

	//var_dump($data);

	if (is_array($data) &&  array_key_exists($hash, $data)) {

		//echo '<br />cache exists for ' . $hash;
		$cache = $data[$hash];

		if (uri_rss_reader_cache_is_expired($cache['time'])) {
			//echo '<br />cache is expired for ' . $hash;
			return false;
		}

		//echo '<br />using cache for ' . $hash;
		
		return $cache['res'];
	}

	//echo '<br />no cache exists for ' . $hash;
	return false;
}


/**
 * Get data from either the cache or from the rss, depending on what's fresher
 *
 * @param str $url the url
 * 
 * @return arr $feed_data the data we want from the feed 
 */
function uri_rss_reader_get_the_feed($url)
{


	$cache = uri_rss_reader_cache_retrieve($url);

	// if we have a good cache, use it
	if ($cache) {
		//echo '<br />using cache for ' . uri_rss_reader_hash_string($url);
		//var_dump($cache);
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
