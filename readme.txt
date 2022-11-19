=== Emoji Settings ===
Contributors: Cybr
Tags: emoji, emojis, emoticon, script, tinymce, mail
Requires at least: 4.2.0
Tested up to: 6.0
Stable tag: 1.2.0
Requires PHP: 5.6.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Emoji Settings adds an option within your Writing Settings page to disable or enable emojis.

== Description ==

= Emoji Settings =

**Simply enable or disable emojis with an option.**

The option can be found at "Settings -> Writing" (`/wp-admin/options-writing.php`).

Works on both multisite networks and single sites.

> <strong>Enabled by default</strong><br>
> This plugin has been written for WordPress Multisite with a WordPress.com like environment in mind.
>
> Because of this, we want to give users full functionality and awesomeness with the least configuration.
> If a user wishes to disable emojis for their site, they can simply do so in their dashboard.

This plugin also fixes incorrect Character Encoding on WordPress installations installed prior to 4.3.0 when emojis are disabled.

= Translating =

You can submit your own translations via the sidebar on this page.

== Installation ==

1. Install Emoji Settings either via the WordPress.org plugin directory, or by uploading the files to your server.
1. Either Network Activate this plugin or activate it on a single site.
1. You can now disable emojis through the admin menu under `wp-admin/options-writing.php`
1. That's it! Enjoy!

== Changelog ==

= 1.2.0 =
* Now properly removes the detection script and styles from all admin screens.
* Now requires PHP 5.6 or later.
* Tested up to WP 6.0.

= 1.1.1 =
* Tested up to WP 4.9.

= 1.0.10 =
* Fixed: When `the_emoji_options` filter was used erroneously, a PHP notice would be cast on every page load.
* Fixed: Updated license links in readme and included license file.
* Fixed: Readme typos.

= 1.0.9 =
* Improved: Overall sanitation (WordPress.com VIP standards).
* Changed: The class loader function caches the filter within as well.
* Updated: POT file.
* Removed: Dutch translation files, these are now provided through WordPress.org.
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
* Improved: The defaults filter is now always casted to array.
* Improved: Reduced plugin memory footprint.
* Cleaned up code.

= 1.0.6 =
* Fixed: PHP Warning when saving options on old WP installations.
* Changed: Improved plugin efficiency.
* Tested up to WP 4.4.0

= 1.0.5 =
* Fixed: New WordPress installations (4.3 and up) don't have the option to turn off smileys. This leads to incorrect character encoding of smiley abbrevations, like :) and :D. Therefor the whole function to encode characters will be removed if emoji's are set to disabled.
* Fixed: Old WordPress installations with WordPress 4.3 and up will automatically set the smileys to off if Emoji support is disabled for the same reason as above. This only has effect after updating the page for the first time.

= 1.0.4 =
* This plugin now supports PHP 5.2 and up

= 1.0.3 =
* Now correctly removes scripts from admin pages

= 1.0.2 =
* Fixed option call priority

= 1.0.1 =
* Fixed html in option page
* Added filter 'the_emoji_options', read "Other Notes" for more information and usage

= 1.0.0 =
* Initial Release

== Other Notes ==

= Filters =

There are two filters for this plugin,
One filter disables the plugin completely, the other filter changes the default settings of Emoji Settings.

Add any of these filter functions to your theme functions.php or template file, or a seperate plugin.

`//* Prevent the plugin from loading
add_action( 'plugins_loaded', 'my_emoji_settings_disable', 4 );
function my_emoji_settings_disable() {
	add_filter( 'cw_emoji_settings_load', '__return_false' );
}`

`//* Modify Default Emoji settings, example
add_filter( 'the_emoji_options', 'my_default_emoji_settings' );
function my_default_emoji_settings( $options ) {

	// Set this to 1 or 0 to enable or disable Emoji output by default. Great for multisite installations.
	// Default is 1.
	$options['default'] = '0';

	return $options;
}`

`//* Override the emoji setting and disable output, example
add_filter( 'the_emoji_options', 'my_disable_emoji' );
function my_disable_emoji( $options ) {
	// Set this to true to disable emoji output anyway regardless of other settings. Set to false to rely on the option in the Writing Settings page.
	// Default is false
	// Example: Disable emojis on home page regardless of settings.
	$options['disable'] = true;

	return $options;
}`

`//* Override the emoji setting and enable output, example
add_filter( 'the_emoji_options', 'my_postpage_emoji_function' );
function my_postpage_emoji_function( $options ) {
	// Set this to true to enable emoji output anyway. Set to false to rely on the option in the Writing Settings page.
	// Default is false
	// Example: Enable emoji's on Post type pages regardless of settings.
	$options['enable'] = true;

	return $options;
}`
