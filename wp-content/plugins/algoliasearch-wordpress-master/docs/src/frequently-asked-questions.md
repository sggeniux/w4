---
title: Frequently Asked Questions
description: Most common issues and how to fix them.
layout: page.html
---
## How it works

This is a living `common questions & answers` section.

Please ask your question on Stack Overflow: http://stackoverflow.com/questions/tagged/algolia+wordpress

If you are a developer, you can also open an issue on github at: https://github.com/algolia/algoliasearch-wordpress. We will regularly add the most frequently asked questions and answers found on Stack Overflow and Github about the Algolia Search plugin for WordPress, and append them to this very page.

If you are desperate and your deadline was yesterday, send us an email at [support+wordpress@algolia.com](mailto:support+wordpress@algolia.com).

**Please give priority to Stack Overflow and Github when asking for support, that way the whole community can benefit from it and you might get a faster answer. If you are having an issue, the chances are high other users might have the same. Let's share!**


## Questions & Answers

### I have more records than I have posts, is that normal?

This is intentional and allows you to fully leverage the Algolia engine.

The plugin takes care of the splitting for you and makes sure that your articles are fully indexed independently from their size.

This means that even if your article is huge and you are searching for a word that is at the very bottom, you will have it as part of the suggested articles.

### Is it possible to disable the post splitting?

Yes, you can turn off the post splitting by defining a constant in your WordPress `wp-config.php` file.

```
define( 'ALGOLIA_SPLIT_POSTS', false );
```

Then you will need to <span class="wp-btn">Re-index everything</span> from the `Indexing` page of the plugin.


### I was using the Beta version of your plugin, what exactly has changed?

Everything. Indeed we realized that the scope of our previous implementation was to large to be able to offer a good user experience.
We have since focused on the core features of this new plugin which allows to seamlessly synchronize any kind of content type you have with Algolia.
We also tried to be more close to the WordPress philosophy, allowing developers to easily extend this plugin so that it can be a good fit for any WordPress project.

We will definitely add features in the future, so if you are really missing something, feel free to suggest

### The previous Beta version of the plugin had some support for WooCommerce, what about this one?

As stated earlier, we narrowed down the scope of this plugin for now. That being said, we really want to offer a flexible way to use Algolia in your WooComerce website in the future.

If you are a developer and have built something based on this plugin, maybe we could work something out together fast enough!

### Can I customize the Autocomplete dropdown look & feel?

Yes. You can find some detailed explanations on [this page](customize-autocomplete.html).

### I have created a custom API key, but it is not accepted as Search-Only API key in the plugin settings.

Creating a dedicated search API key is indeed a good practice. If you want to be able to use that new generated API key as the Search-Only API key, you have to make sure it only has the `search` ACL privilege.
For security reasons, we reject every filled Search-Only API key that has additional privileges.

Secondly, we also require that the provided search API key has the TTL set to 0 (standing for unlimited validity in time). We do not want your users to get search outage.

### Will this plugin be compatible with my WordPress theme?

Yes. Actually this plugin introduces 2 main user oriented features:
- Native Search: Which overrides the default WordPress search with Algolia,
- Autocomplete: Which provides the user with an 'as you type' instant search experience.

In the first case, this plugin hooks into the WordPress native search feature. As a result, this will not impact your template because we only act on the backend side of things.

In the second case, we simply provide a dropdown menu that will be displayed underneath your search bar as you type. We have provided default scoped CSS rules so that the look and feel stays slick across different themes.

If you have any kind of issue related to default appearance, please send us a link to your website so that we can optimize our stylesheet.

### My data is out of sync with Algolia

Sometimes your WordPress content gets out of sync with Algolia. For example if you are changing your permalink templates, all your URLs will change and Algolia will not know about it.

Content also gets inconsistent when you use filters to change settings or attributes to push to Algolia.

In that case you can simply re-index your content.

### Can I use one Algolia account for multiple WordPress sites?

Definitely! In the same fashion that WordPress lets you prefix all database tables, we allow you to prefix every indices in Algolia.

This can be configured on the `Indexing` section of the plugin.

### Why doesn't my autocomplete dropdown show up?

1. First of all ensure the autocomplete feature is turned on on the Autocomplete page of the admin UI,
1. Make sure you actually selected at least one index to display from that same page, and ensure those indices were created in Algolia,
1. Make sure your theme calls the `wp_footer()` method in your template files.

### How do I resolve `wp_remote()` errors on `indexing` admin page?

It really depends what error you are getting, but here are a few know issues:

**You are using an SSL certificate (your website url starts with `https://`):**

Our plugin uses cURL and the underlying OpenSSL library to loop over the queued indexing tasks.

If the OpenSSL library is too old, then you might face cURL error 35 with messages containing `handshake failure`, or `unknown protocol`.

In that case you should ask your hosting provider to upgrade your cURL/OpenSSL version.

If you are unable to upgrade your cURL/OpenSSL versions, and if your website can also be accessed over 'HTTP', you can force the loopback to access your website over HTTP by adding the following line to your wp-config.php file: `define( 'ALGOLIA_LOOPBACK_HTTP', true );`

**Htaccess password protected:**

If your /wp-admin section of your website is protected with Basic HTTP Authentication, you can use the `algolia_loopback_request_args` filter to add your username and password to the remote calls headers.

```php
<?php
// In your current active theme functions.php.
define( 'MY_USERNAME', 'test' );
define( 'MY_PASSWORD', 'test' );

function custom_loopback_request_args( array $request_args ) {
	$request_args['headers']['Authorization'] = 'Basic ' . base64_encode( MY_USERNAME . ':' . MY_PASSWORD );

	return $request_args;
}

add_filter( 'algolia_loopback_request_args', 'custom_loopback_request_args' );
```

**You are using Docker:**

If you are using Docker, you should make sure that the domain name you are using is listed in the `/etc/hosts` file of your container.

Port forwarding is currently unsupported, so you should use port 80 or 443 depending on if you are using http or https to access your website.

**Does the instant search results work with IE9?**

The default implementation will break on IE9. You can use the `useHash` option of the instantsearch.js library to enable support for IE9.

See https://github.com/algolia/algoliasearch-wordpress/pull/375 for an example of how to enable IE9 support.

### Can I remove non required thumbnail sizes from the records?

By default the plugin pushes all the thumbnail sizes available so that you can easily switch the displayed format in your frontend integration.

If you want to optimize the records size you can definitely remove non-used sizes.

Let's say you only use `thumbnail` and the `medium` thumbnail sizes, here is how to remove all the others:

```php
<?php

/**
 * @param array $shared_attributes
 *
 * @return array
 */
function vm_post_shared_attributes( array $shared_attributes ) {
	$keep_sizes = array( 'thumbnail', 'medium' );
	$images = array();
	foreach ( $shared_attributes['images'] as $size => $info ) {
		if ( in_array( $size, $keep_sizes ) ) {
			$images[$size] = $info;
		}
	}

	$shared_attributes['images'] = $images;

	return $shared_attributes;
}

// Remove the un-necessary sizes from the posts index.
add_filter( 'algolia_post_shared_attributes', 'vm_post_shared_attributes' );

// Also remove the un-necessary sizes from the searchable posts index.
add_filter( 'algolia_searchable_post_shared_attributes', 'vm_post_shared_attributes' );
```

### Can I push my custom user attributes?

Yes, and you can do so by using the `algolia_user_record` filter:

```
<?php

/**
 * @param array   $record
 * @param WP_User $user
 *
 * @return array
 */
function custom_user_record( array $record, WP_User $user ) {
	$record['custom_field'] = get_user_meta( $user->ID, 'custom_field', true );
	/* Add as many as you want. */

	return $record;
}

add_filter( 'algolia_user_record', 'custom_user_record', 10, 2 );
```

### My case is not listed here, what to do?

If your problem is covered here, please submit an issue with the error details here: https://github.com/algolia/algoliasearch-wordpress/issues


