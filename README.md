# URI RSS Reader

The URI RSS Reader plugin displays an RSS news feed via a shortcode.

## How do I use the shortcode?
Paste the shortcode into a page to display the news feed. 

## Parameters:

### url 
The url of the rss feed. \
default: null \
Ex: ```[uri-rss-reader url="https://www.uri.edu/news/tag/college-of-arts-and-sciences/feed"]```

### display
How many posts to display.\
default: 20 \
Ex: ```[uri-rss-reader url="{url}" display="5"]```

### exclude
Any posts to exclude from display. \
default: null \
Ex: ```[uri-rss-reader url="{url}" exclude="{first_url}, {second_url}"]```

### cache 
Cache time-out. \
default: 1 hour \
Ex: ```[uri-rss-reader url="{url}" cache="30 minutes"]```

### include_excerpt
Display the excerpt. \
default: true \
Ex: ```[uri-rss-reader url="{url}" include_excerpt="false"]```

### include_date
Display the date. \
default: true \
Ex: ```[uri-rss-reader url="{url}" include_date="false"]```

### include_image 
Display the thumbnail image. \
default: true \
Ex: ```[uri-rss-reader url="{url}" include_image="false"]```


## Plugin Details

[![Master Build Status](https://travis-ci.com/uriweb/uri-plugin-template.svg?branch=master "Master build status")](https://travis-ci.com/uriweb/uri-plugin-template)
[![CodeFactor](https://www.codefactor.io/repository/github/uriweb/uri-plugin-template/badge/master)](https://www.codefactor.io/repository/github/uriweb/uri-plugin-template/overview/master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/77712193bd8643f88fad1fbdc8a02c87)](https://www.codacy.com/app/uriweb/uri-plugin-template?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=uriweb/uri-plugin-template&amp;utm_campaign=Badge_Grade)
[![devDependencies Status](https://david-dm.org/uriweb/uri-plugin-template/dev-status.svg)](https://david-dm.org/uriweb/uri-plugin-template?type=dev)

URI RSS Reader Plugin  
An RSS reader plugin that displays a news feed via shortcode.

Contributors: Alexandra Gauss \
Tags: plugins  
Requires at least: 4.0  
Tested up to: 6.6.2  
Stable tag: 0.1.0  
