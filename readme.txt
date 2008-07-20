=== Trackping Separator ===
Contributors: mk_is_here
Donate link: http://mk.netgenes.org/my-plugins/
Tags: comments, trackback, pingback 
Requires at least: 1.5
Tested up to: 2.6.0
Stable tag: trunk

A plugin to help separate trackbacks and pingbacks from comments.

== Description ==

Trackping Separator, by its name, is a WordPress Plugin that could separate trackbacks/pingbacks from user comments. Useful functions to list trackbacks are also provided. 

== Installation ==

1. Extract the zip into the wp-content/plugins directory
1. Activate the plugin through the Admin panel of your WordPress

== Frequently Asked Questions ==

= How can I display the date for each trackback/pingback? =

Use the variable %date in listtrackback() template function. There's an extra parameter for the function to specify the date format. For example, if you want to display the date in the trackping loop, the function call should be:

`<pre><?php listtrackpings('', '<li id="trackback-%id"><a href="%url">%origin</a>(%date)<br />%content</li>'); ?></pre>`
