<?php

/**
 * URI RSS READER PARSE
 *
 * @package uri-rss-reader
 */
function uri_rss_reader_load_xml($feed_url)
{
    // Load the XML from the URL
    $xml = simplexml_load_file($feed_url);
    return $xml;
}

function uri_rss_reader_feed_list ( $xml ) {
    echo '<h2>' . $xml->channel->title . '</h2>';

foreach ($xml->channel->item as $item) {
    echo '<h3><a href="' . $item->link . '">' . $item->title . "</a></h3>";
    echo "<p>" . $item->description . "</p>";
    //$thumbAttr = $item->children('media', true)->thumbnail->attributes();
    $thumbAttr = $item->children('http://search.yahoo.com/mrss/')->thumbnail->attributes();
    if ($thumbAttr) {
        echo '<img src="' . $thumbAttr['url'] . '">';
    };
}
}