---
title: Index Schema
description: Understand how we format the data pushed to Algolia.
layout: page.html
---

## Introduction

This plugins formats your data in an optimal and extensible format so that it can better serve your WordPress search experience.

You will find on this page some explanations about how we format your data, how we configure your Algolia indices, and how you can customize it.


## Posts

WordPress `Posts` core feature allows developers to easily create custom content types.

Every third party plugin offering new post types will automatically be available to the Algolia Search plugin.

By default here are the attributes we push to Algolia for every post:

|Attribute Name|Description
|-|-
|objectID|The unique identifier for this record.
|post_id|The unique identifier for the post.
|post_type|The post type slug.
|post_type_label|The post type nice-name.
|post_title|The title of the post.
|post_excerpt|The excerpt of the post. An empty string if not available.
|post_date|The first publication date as a unix timestamp.
|post_modified|The last time the post was updated as a unix timestamp.
|comment_count|The number of comments for the post.
|post_author|The post author as an array containing the `author_ID`, the `display_name` and the `user_url`.
|thumbnail_url|The url to the featured image of the post.
|permalink|The url to the public page of the post.
|post_mime_type|The Mime Type of the post, available only for Medias (attachments).
|taxonomy_post_tag|An array of strings representing the post tags.
|taxonomy_category|An array of strings representing categories the post is attached to.
|is_sticky|1 if the post is marked as sticky, 0 otherwise.

The above attributes are what we call `shared attributes`.

Algolia is very performing when searching into small chunks of text. As posts can become large pieces of text, we have a mechanism splitting your posts into several Algolia records.

Each record of a same post would have the same shared attributes and the following `content attributes`.

|Attribute Name|Description
|-|-
|title{1-6}|Content found in hierarchical dom elements of your post.
|content|Content found it the bottom most hierarchical level in your post.

To extract the content of the generated post DOM, we use the following library: https://github.com/algolia/php-dom-parser.

By default, the parser will fetch content in your heading tags, from h1 to h6, to respectively fill the title1 to title 6.
Then it will fetch the content in p, ul, ol, dl, table tags. We excluded the `pre` tag in this plugin.

If you want to change the way you data is fetched, you can use an available filter to customize the parser:

```php
<?php

function my_custom_parser( Algolia\DOMParser $parser ) {
	// Custom selectors.
	$parser->setAttributeSelectors( array(
       'title1'  => 'title',
       'title2'  => 'div.heading',
       'title3'  => 'p.sub-header',
       'title4'  => 'h4',
       'title5'  => 'h5',
       'title6'  => 'h6',
       'content' => 'p, ul, ol, dl, table',
   ) );

   // Custom exlusion rules.
   $parser->setExcludeSelectors( array(
	'pre', '.related-articles', '#toc', '#social-medias'
   ) );

   return $parser;
}

add_filter( 'algolia_post_parser', 'my_custom_parser' );

```

## Terms

As there are no huge chunks of text in terms, we have one record per item.

Here are the default attributes we push:

|Attribute Name|Description
|-|-
|objectID|The unique identifier for this record.
|term_id|The unique identifier for the term.
|taxonomy|The taxonomy slug.
|name|The term name.
|description|The term description.
|slug|The slug of the term.
|posts_count|The number of times this term was assigned to a post.
|permalink|The url to the public page of the term.

## Users

No chunks of text for users, so one record per item:

|Attribute Name|Description
|-|-
|objectID|The unique identifier for this record.
|user_id|The unique identifier for the user.
|display_name|The display name for the user.
|posts_url|URL to the page listing all posts of the author.
|description|The bio of the author.
|posts_count|The number of posts written by the user.
|avatar_url|The URL of the user's avatar picture.



