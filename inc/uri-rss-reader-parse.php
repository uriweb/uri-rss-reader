<?php

/**
 * URI RSS READER PARSE
 *
 * @package uri-rss-reader
 */

 /**
  * Get the XML data from the URL
  */
function uri_rss_reader_get_xml ($url) {
    $response = wp_safe_remote_get($url);
    $body = (wp_remote_retrieve_body($response));
    $rss = simplexml_load_string($body);
    return $rss;
}

/**
 * Load the XML data from the URL of the feed
 * @param str feed url
 * @param arr urls to exclude from feed
 * @return arr feed data
 */
function uri_rss_reader_get_array($rss, $exclude_urls)
{

    // Initialize an array to hold the feed data
    $array_feed = [];

    // Loop through each RSS item
    foreach ($rss->channel->item as $item) {
        // Check if the <link> element matches the excluded URL
        if (!in_array((string)$item->link, $exclude_urls)) {
            $feed_item = [
                'title' => (string)$item->title,
                'link'  => (string)$item->link,
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
    }
    return $array_feed;
}



/*
        if ( isset( $response->channel ) && !empty( $response->channel ) && '200' == wp_remote_retrieve_response_code( $response ) ) {
              // hooray, all is well!
              $body = ( wp_remote_retrieve_body ( $response ) );
              $xml_string = simplexml_load_string($body);
              $json_obj = json_encode($xml_string);
              return json_decode($json_obj, TRUE );
          } else {
      
              // still here?  Then we have an error condition
              //this doesn't work correctly
      
              if ( is_wp_error ( $response ) ) {
                  $error_message = $response->get_error_message();
                  echo 'There was an error with the RSS Reader Plugin: ' . $error_message;
                  return FALSE;
              }
              if ( '200' != wp_remote_retrieve_response_code( $response ) ) {
                  echo $response;
                  return FALSE;
              }
      
              // still here?  the error condition is indeed unexpected
              echo "Empty response from server?";
              return FALSE;
          }
      */

/**
 * Render the feed
 * @param arr feed data
 * @param arr attributes from shortcode
 * @return arr feed data
 */
function uri_rss_reader_display($feed_data, $attributes)
{
    ob_start();
    print $attributes['before'];
    //loop through array
    ?>
    
    <div class="uri-rss-reader-feed">
    <h3 class="latest-news">Latest News</h3>
        <?php

    foreach ($feed_data as $element) {
?>
        <div class="uri-rss-reader-item">
            <?php
            if (isset($element['media:thumbnail'])) {
            ?>
                <div class="uri-rss-reader-image">
                    <img src="<?php print $element['media:thumbnail']['url'] ?>" alt="<?php print $element['media:thumbnail']['alt'] ?>">
                </div>
            <?php
            } else {
                ?>
<div class="no-thumbnail"></div>
                <?php
            }
            ?>
            <div class="uri-rss-reader-title-link">
                <a href=" <?php print $element['link'] ?> "><?php print $element['title'] ?></a>
            </div>
        </div>
<?php
    }
    ?> </div>
    <?php
    print $attributes['after'];
    $feed_output = ob_get_clean();
    return $feed_output;
}
