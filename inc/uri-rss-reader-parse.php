<?php

/**
 * URI RSS READER PARSE
 *
 * @package uri-rss-reader
 */

/**
 * Get the XML data from the URL
 * @param str $url from the shortcode ex. https://www.uri.edu/news/tags/engineering/feed
 */

function uri_rss_reader_get_xml($url)
{
    $response = wp_safe_remote_get($url);

    if ($response['headers']['content-type'] == 'application/rss+xml; charset=UTF-8' && '200' == wp_remote_retrieve_response_code($response)) {
        // hooray, all is well!
        $body = (wp_remote_retrieve_body($response));
        $rss = simplexml_load_string($body);
        return $rss;
    } else {

        // still here?  Then we have an error condition

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo 'There was an error with the RSS Reader Plugin: ' . $error_message;
            return FALSE;
        }
        if ('200' != wp_remote_retrieve_response_code($response)) {
            echo $response;
            return FALSE;
        }
        if ($response['headers']['content-type'] == 'text/html; charset=UTF-8') {
            echo "Not a valid RSS feed.";
            return FALSE;
        }

        // still here?  the error condition is indeed unexpected
        echo "Server not responding?";
        return FALSE;
    }
}

/**
 * Load the XML data from the URL of the feed and parse out the title, link, image, and alt-text it into an array
 * @param obj xml feed data
 * @return arr feed data
 */
function uri_rss_reader_build_array($rss)
{

    $array_feed = [];

    // Loop through each RSS item
    foreach ($rss->channel->item as $item) {
        $feed_item = [
            'title' => (string)$item->title,
            'link'  => (string)$item->link,
            'description' => (string)$item->description,
            'date' => substr((string)$item->pubDate, 0, 16),
        ];

        // Check if media:thumbnail exists and extract the URL and alt attribute
        if (isset($item->children('media', true)->thumbnail)) {
            $thumbnail = $item->children('http://search.yahoo.com/mrss/')->thumbnail->attributes();
            $feed_item['media:thumbnail'] = [
                'url' => (string)$thumbnail['url'],
                'alt' => (string)$thumbnail['alt'] ?: 'Featured image for ' . $item->title
            ];
        }
        // Add the item to the array feed
        $array_feed[] = $feed_item;
    }
    return $array_feed;
}


/**
 * Render the feed
 * @param arr of the feed data - title, link, image, alt-text
 * @param arr attributes from shortcode
 * @param arr urls to exclude in the feed
 * @return arr feed output to display
 */
function uri_rss_reader_display($feed_data, $attributes, $exclude_urls)
{

    // Get the number of feed items to display from shortcode & start a counter
    $number = $attributes['display'];
    $count = 0;

    // Start the display
    ob_start();
    print $attributes['before'];
?>

    <div class="uri-rss-reader-feed">
        <h3 class="latest-news">Latest News</h3>
        <?php
        //loop through array
        foreach ($feed_data as $element) {
            //Check for excluded urls
            if (!in_array($element['link'], $exclude_urls)) {
                // Check against number to display
                if ($count++ < $number) {
        ?>
                    <div class="uri-rss-reader-item">
                        <?php
                        // Check if images are chosen to be displayed 
                        if ($attributes['image'] == 'true') {
                            // Check if thumbnail exists
                            if (isset($element['media:thumbnail'])) {
                        ?>
                                <div class="uri-rss-reader-image">
                                    <img src="<?php print $element['media:thumbnail']['url'] ?>" alt="<?php print $element['media:thumbnail']['alt'] ?>">
                                </div>
                            <?php
                                // If no thumbanil exists, display no-thumbnail div
                            } else {
                            ?>
                                <div class="no-thumbnail">
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="uri-rss-reader-title-link">
                            <a href=" <?php print $element['link'] ?> "><?php print $element['title'] ?></a>
                        </div>
                        <?php
                        //Check if date should be displayed
                        if ($attributes['date'] == 'true') {
                        ?>
                            <div class="date">
                                <p> <?php print $element['date'] ?></p>
                            </div>
                        <?php
                        }
                        if ($attributes['description'] == 'true') {
                        ?>
                            <div class="description">
                                <p> <?php print $element['description'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
        <?php
                }
            }
        }
        ?>
    </div>
<?php
    print $attributes['after'];
    $feed_output = ob_get_clean();
    return $feed_output;
}
