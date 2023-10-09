=== Emoji Settings ===
Contributors: Cybr
Donate link: https://github.com/sponsors/sybrew
Tags: Emoji, emojis, emoticon, script, tinymce, mail, scrips, prefetch, twemoji
Requires at least: 5.5
Tested up to: 6.4
Stable tag: 2.0.1
Requires PHP: 7.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Emoji Settings adds an option to your Writing Settings page to toggle emoji conversion to images.

== Description ==

**Quickly enable or disable emojis conversion with an option.**

When you disable the option, Emoji Settings stops the conversion of ASCII smilies like `:)` and `:D` to images on any WordPress installation. This plugin also prevents changing real emojis to Twemoji (Twitter) images. It achieves this by removing several default WordPress scripts.

You can find the option at "Settings > Writing" (`/wp-admin/options-writing.php`).

This plugin does not prevent real emojis (inserted via an emoji keyboard) from being stored and outputted on your website.

= Emoji conversion enabled by default =

I wrote this plugin with a WordPress.com-like environment in mind, giving users an option without overriding standard WordPress behavior.

You can change this behavior via filter `cw_emoji_overrides`. Refer to the code for instructions.

= Does more than "Disable Emojis" =

Emoji Settings also fixes Character Encoding issues on sites originally installed with WP 4.2 or lower. And this plugin correctly removes the conversion of emojis in the admin area, for example, from post titles.

= Translating =

You can contribute by translating Emoji Settings via the sidebar on this page.

== Installation ==

1. Install Emoji Settings either via the WordPress.org plugin directory or by uploading the files to your server.
1. Either Network Activate this plugin or activate it on a single site.
1. You can now disable emojis through the admin menu under `wp-admin/options-writing.php`.
1. That's it! Enjoy!

== Frequently Asked Questions ==

= How do I disable emojis by default =

You can implement this filter to achieve that:

`add_filter( 'cw_emoji_overrides', function( $overrides ) {
	$overrides['default'] = '0'; // Set disabled by default.
	return $overrides;
} );`

== Changelog ==

= 2.0.1 =
* Now stores the default setting in the database. This prevents extraneous lookups for unconfigured websites.
* When emojis conversion is disabled, the string `'0'` will now be stored in the database, instead of an empty string `''`. This will only take effect when the writing settings are resaved.

= 2.0.0 =
* Rewritten for improved performance.
* Now requires PHP 7.2 or later.
* Now requires WP 5.5 or later because it adds tests against PHP support.
* All function and class names have changed due to added namespacing, hence the major version bump.
* Added filter `cw_emoji_overrides`, accepts array `[ 'default' => string 1|0, 'force_support' => ?bool ]`.
* Removed confusing filter `the_emoji_options`.
* Changed the option label from "Enable emoji support" to "Enable emoji conversion": this plugin prevents the conversion; it does not prevent actual emojis from being stored and printed.

= 1.2.0 =
* Now properly removes the detection script and styles from all admin screens.
* Now requires PHP 5.6 or later.
* Tested up to WP 6.0.

= 1.1.1 =
* Tested up to WP 4.9.

= 1.0.10 =
* Fixed: When `the_emoji_options` filter was incorrectly used, a PHP notice would be cast on every page load.
* Fixed: Updated license links in readme and included license file.
* Fixed: Readme typos.

= 1.0.9 =
* Improved: Overall sanitation (WordPress.com VIP standards).
* Changed: The class loader function caches the filter within as well.
* Updated: POT file.
* Removed: Dutch translation files; these are now provided through WordPress.org.
* Other: Cleaned up code.
* Note: Plugin license is upgraded from GPLv2+ to GPLv3.

= 1.0.8 =
* Improved: (performance) Saving Writing Settings no longer casts the Emoji Setting to an 1 or 0 string when it's already an 1 or 0 string.
* Improved: (performance) Removed boolean type casting on a boolean if statement.
* Other: This plugin's description on the activation page is much shorter.

= 1.0.7 =
* Note: With more than 1000 hours of extra PHP programming experience, I've updated this plugin to the latest WordPress and PHP coding standards.
* Added: WordPress.org translation compatibility.
* Added: Local PHP option caching.
* Added: Class caching.
* Added: New filter. See "Other Notes" on this plugin's homepage.
* Added: POT translation file.
* Changed: Plugin translation domain.
* Updated: Translation files.
* Improved: The defaults filter is now always cast to an array.
* Improved: Reduced plugin memory footprint.
* Cleaned up code.

= 1.0.6 =
* Fixed: PHP Warning when saving options on old WP installations.
* Changed: Improved plugin efficiency.
* Tested up to WP 4.4.0

= 1.0.5 =
* Fixed: New WordPress installations (4.3 and up) don't have the option to turn off smileys. Those before now have incorrect character encoding of smiley abbreviations, like :) and :D, when emojis are disabled. So, when you disable emojis, smilies will also be disabled.
* Improved: When disabling emojis, the smilies setting will also be disabled to reflect the workings of this plugin visually.

= 1.0.4 =
* This plugin now supports PHP 5.2 and up.

= 1.0.3 =
* Now correctly removes scripts from admin pages.

= 1.0.2 =
* Fixed option call priority.

= 1.0.1 =
* Fixed HTML on the options page.
* Added filter `the_emoji_options`.

= 1.0.0 =
* Initial Release
