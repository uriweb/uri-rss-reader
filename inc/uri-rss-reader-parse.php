<?php

/**
 * URI RSS READER PARSE
 *
 * @package uri-rss-reader
 */
function uri_rss_reader_load_xml($feed_data)
{
    // Load the XML from the URL
    $xml = simplexml_load_file($feed_data);
    return $xml;
}

function uri_rss_reader_feed_list($xml, $exclude_urls)
{
    foreach ($xml->channel->item as $item) {
        // Check if the <link> element matches an excluded URL
        if (!in_array((string)$item->link, $exclude_urls)) {
            // Display the item
            echo '<div class="uri-rss-reader-item">';
            // Check if the <thumbnail> element exists
            $thumbnail = $item->children('http://search.yahoo.com/mrss/')->thumbnail;

            if ($thumbnail && isset($thumbnail->attributes()['url'])) {
                $thumbAttr = $thumbnail->attributes();
                echo '<div class="uri-rss-reader-thumbnail">';
                echo '<img src="' . $thumbAttr['url'] . '">';
                //end div class="uri-rss-reader-thumbnail
                echo '</div>';
            }
            echo '<div class="uri-rss-reader-title">';
            echo '<h3><a href="' . $item->link . '">' . $item->title . "</a></h3>";
            echo '</div>';
            echo '<div class="uri-rss-reader-excerpt">';
            echo "<p>" . $item->description . "</p>";
            echo '</div>';
            //end uri-rss-reader-item
            echo '</div>'; 

        }
    }
}
