<?php
/**
 * Plugin Name: Emoji Settings
 * Plugin URI: https://wordpress.org/plugins/emoji-settings/
 * Description: Adds an option to your Writing Settings page to enable or disable emoji conversion to images.
 * Author: Sybre Waaijer
 * Author URI: https://cyberwire.nl/
 * Version: 3.0.0
 * License: GLPv3
 * Text Domain: emoji-settings
 * Domain Path: /language
 * Requires at least: 5.5
 * Requires PHP: 7.4.0
 *
 * @package CyberWire/Emoji_Settings
 */

namespace CyberWire\Emoji_Settings;

/**
 * Emoji Settings plugin
 * Copyright (C) 2015 - 2024 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

\add_action( 'init', __NAMESPACE__ . '\disable_emojis', 4 );
\add_action( 'admin_init', __NAMESPACE__ . '\load_plugin_textdomain', 9 );
\add_filter( 'admin_init', __NAMESPACE__ . '\register_admin_fields' );

/**
 * Disable the emoji output based on option
 *
 * @hook init 4
 * @since 3.0.0
 */
function disable_emojis() {

	$overrides = get_setting_overrides();

	$keep_emoji = \is_null( $overrides['force_support'] )
		? \get_option( 'enable_emoji', $overrides['default'] )
		: $overrides['force_support'];

	if ( ! $keep_emoji ) {
		\add_action( 'admin_init', __NAMESPACE__ . '\disable_admin_emojis' );

		/**
		 * @credits https://wordpress.org/plugins/disable-emojis/
		 */
		\remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Front-end browser support detection script
		\remove_action( 'embed_head', 'print_emoji_detection_script' ); // Embed browser support detection script
		\remove_action( 'wp_print_styles', 'print_emoji_styles' ); // Emoji styles
		\remove_filter( 'the_content_feed', 'wp_staticize_emoji' ); // Remove from feed, this is bad behaviour!
		\remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); // Remove from feed, this is bad behaviour!
		\remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' ); // Remove from mail
		\add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\disable_emojis_tinymce' ); // Remove from tinymce

		// Remove DNS prefetch s.w.org (used for emojis, since WP 4.7)
		\add_filter( 'emoji_svg_url', '__return_false' );

		if ( \get_site_option( 'initial_db_version' ) >= 32453 )
			\remove_action( 'init', 'smilies_init', 5 ); // This removes the ascii to smiley conversion
	}
}

/**
 * Loads the plugin textdomain in the admin area.
 *
 * @hook admin_init 9
 * @since 3.0.0
 */
function load_plugin_textdomain() {
	// File located in plugin folder emoji-settings/language/
	\load_plugin_textdomain(
		'emoji-settings',
		false,
		\dirname( \plugin_basename( __FILE__ ) ) . '/language',
	);
}

/**
 * Adds a new fields to wp-admin/options-writing.php page.
 *
 * @hook admin_init 10
 * @since 3.0.0
 */
function register_admin_fields() {

	\register_setting(
		'writing',
		'enable_emoji',
		[
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_emoji_setting',
		],
	);

	if ( false === \get_option( 'enable_emoji' ) )
		\add_option( 'enable_emoji', get_setting_overrides()['default'] );

	\add_settings_field(
		'enable_emoji',
		\esc_html__( 'Emoji Conversion', 'emoji-settings' ),
		__NAMESPACE__ . '\output_settings_field',
		'writing',
	);
}

/**
 * Returns the filtered options.
 *
 * @since 3.0.0
 *
 * @return array The emoji filters.
 */
function get_setting_overrides() {
	/**
	 * @since 2.0.0
	 * @param array $settings : {
	 *     'default'       => @param string '1' or '0', the default admin setting (user can override this via option).
	 *                                      '1' means enable emoji support. '0' means disable emoji support.
	 *     'force_support' => @param ?bool  Set to true to force-enable emoji support (ignore user option),
	 *                                      or false to force-disable emoji support.
	 *                                      Any unsupported value is casted to bool.
	 * }
	 */
	return (array) \apply_filters(
		'cw_emoji_overrides',
		[
			'default'       => '1',
			'force_support' => null,
		]
	);
}

/**
 * Disable the emoji output on admin screens.
 *
 * @hook admin_init 10
 * @since 3.0.0
 */
function disable_admin_emojis() {
	\remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); // Admin browser support detection script
	\remove_action( 'admin_print_styles', 'print_emoji_styles' ); // Admin emoji styles
}

/**
 * Filters tinyMCE plugins in order to remove wpmemoji support.
 *
 * @hook tiny_mce_plugins 10
 * @since 3.0.0
 *
 * @param array $plugins The tinyMCE plugins.
 * @return array the tinyMCE plugins without wpemoji.
 */
function disable_emojis_tinymce( $plugins ) {
	return array_diff( $plugins, [ 'wpemoji' ] );
}

/**
 * Sanitizes the emoji setting.
 *
 * Also, when smilies are enabled, but emojis are disabled, disable smilies. Only affects sites installed with WP<4.3.
 * This Prevents unreadable character output caused by 'use_smilies' option.
 *
 * @hook sanitize_option_enable_emoji 10
 * @since 3.0.0
 *
 * @param mixed $setting The emoji settings option.
 * @return array $options The options to save.
 */
function sanitize_emoji_setting( $setting ) {

	$setting = (string) (int) $setting;

	// phpcs:disable, WordPress.Security.NonceVerification -- checked by default settings page handler.
	if (
		   '1' === ( $_POST['use_smilies'] ?? null )  // Smilies are enabled.
		&& '1' !== $setting // But emojis are disabled.
		&& \get_site_option( 'initial_db_version' ) < 32453 // Test if initial is below WP 4.3.0
	) {
		\update_option( 'use_smilies', '0', true );
	}
	// phpcs:enable, WordPress.Security.NonceVerification

	return $setting;
}

/**
 * Outputs HTML for settings.
 *
 * @since 3.0.0
 */
function output_settings_field() {
	vprintf(
		'<fieldset>
			<legend class="screen-reader-text"><span>%1$s</span></legend>
			<label for="enable_emoji">
				<input name="enable_emoji" type="checkbox" id="enable_emoji" value="1" %2$s />
				%3$s
			</label>
		</fieldset>',
		[
			\esc_html__( 'Emoji Conversion', 'emoji-settings' ),
			\checked(
				'1',
				\get_option( 'enable_emoji', get_setting_overrides()['default'] ),
				false,
			),
			\esc_html__( 'Enable text to emoji conversion', 'emoji-settings' ),
		]
	);
}
