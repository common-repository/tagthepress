=== TagThePress ===
Contributors: vividvisions
Tags: tag,tagging,post,tagthe.net,related,automatic 
Requires at least: 2.5
Tested up to: 3.2.1
Stable tag: 3.0

WordPress Plugin for tag suggestions using tagthe.net

== Description ==

With this plugin, you will be able to use the Web service [tagthe.net](http://tagthe.net/) for your posts. Just click on `Fetch tags` and the title, content and optional excerpt of your post will be analyzed by tagthe.net in order to find words that could be useful as tags. 

It currently works with English, German, French and Spanish content. 

== Installation ==

1. Extract the `tagthepress` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What is tagthe.net? =

[tagthe.net](http://tagthe.net/) is a Web service intended to provide you with tags for your contents. You can use it in your applications, in other webservices or just play around with it.

= Which languages are supported? =

Currently English, German, French and Spanish. 

== Screenshots ==

1. This screenshot shows the new button "Fetch tags".

== Changelog ==  

= 3.0 =
* TagThePress finally works with WordPress 3.2.*.
* Minor changes.

= 2.2 =
* TagThePress now also works when the wp-config.php file is not located in the WordPress root directory.

= 2.1 =
* Compatible with WordPress 2.8.2.
* Added better error reporting.
* Added workaround regarding the HTML comments added to the Ajax response by WP Super Cache.
* Changed MIME-type of the Ajax reponse to application/json.

= 2.0 =
* Compatible with WordPress 2.8.
* Added select box for the maximum number of returned tags (5 to 100).
* Added error messages in case of timeouts.

= 1.2 =
* Finally fixed bug when articles exceed a certain length.
* Fixed bug which could prevent the "Fetch tags" button from being shown after saving the article.
* Added tag suggestions for persons and locations.
* Increased maximum number of returned tags to 15.

= 1.1 =
* Fixed bug when not using graphical editor
* Added workaround for Internet Explorer which has a [maximum URI length of 2,048 characters](http://support.microsoft.com/kb/208427/EN-US/). Currently, the content gets brutally cut at the last "space" character within this range, when this length is exceeded.
* Fixed bug which could prevent the German localization from being loaded

= 1.0 =
* Initial release
* Added localization (English, German)
* Tested with Internet Explorer, Firefox, Safari and Opera
