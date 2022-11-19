=== Emoji Settings ===
Contributors: Cybr
Tags: Emoji, emojis, emoticon, script, tinymce, mail
Requires at least: 4.2.0
Tested up to: 6.1
Stable tag: 2.0.0
Requires PHP: 7.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Emoji Settings adds an option to your Writing Settings page to enable or disable emoji conversion to images.

== Description ==

**Simply enable or disable emojis conversion with an option.**

This plugin stops the conversion of ASCII smilies like `:)` and `:D` to images on any WordPress installation.

You can find the option at "Settings > Writing" (`/wp-admin/options-writing.php`).

This plugin does not prevent real emojis from being stored and printed.

= Emojis are enabled by default =

This plugin has been written with a WordPress.com-like environment in mind. We want to give users the option but not override default WordPress behavior. If you or your user wishes to disable emojis for their site, they can do so in their dashboard.

You can override standard behavior via filter `cw_emoji_overrides`. Refer to the code for instructions.

= Does more than "Disable Emojis (GDPR friendly)" =

This plugin also fixes incorrect Character Encoding on WordPress installations installed before 4.3.0 when emojis are disabled.
This plugin also removes the conversion of emojis in the admin area, for example, from post titles.

= Translating =

You can submit your translations via the sidebar on this page.

== Installation ==

1. Install Emoji Settings either via the WordPress.org plugin directory or by uploading the files to your server.
1. Either Network Activate this plugin or activate it on a single site.
1. You can now disable emojis through the admin menu under `wp-admin/options-writing.php`.
1. That's it! Enjoy!

== Changelog ==

= 2.0.0 =
* Rewritten for improved performance.
* All function and class names have changed due to added namespacing, hence the major version bump.
* Now requires PHP 7.2 or later.
* Added filter `cw_emoji_overrides`, accepts array `[ 'default' => string 1|0, 'force_support' => ?bool ]`.
* Removed confusing filter `the_emoji_options`.
* Changed the option label from "Enable emoji support" to "Enable emoji conversion"; this plugin prevents the conversion, it does not prevent actual emojis from being stored and printed.

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
