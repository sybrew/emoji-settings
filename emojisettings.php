<?php
/**
 * Plugin Name: Emoji Settings
 * Plugin URI: https://wordpress.org/plugins/emoji-settings/
 * Description: Adds an option to disable or enable the emoji output in Writing Settings.
 * Author: Sybre Waaijer
 * Author URI: https://cyberwire.nl/
 * Version: 1.0.8
 * License: GLPv2 or later
 * Text Domain: emoji-settings
 * Domain Path: /language
 */

add_action( 'plugins_loaded', 'cw_emoji_settings_locale' );
/**
* Plugin locale 'emoji-settings'
*
* File located in plugin folder emoji-settings/language/
*
* @since 1.0.0
*/
function cw_emoji_settings_locale() {
	load_plugin_textdomain( 'emoji-settings', false, basename( dirname( __FILE__ ) ) . '/language/' );
}

add_action( 'plugins_loaded', 'cw_emoji_settings', 5 );
/**
 * Load and cache Emoji_Settings_Field class
 *
 * @action plugins_loaded
 * @priority 5 Use anything above 5, or any action later than plugins_loaded and
 * you can access the class and functions.
 *
 * @staticvar object $cw_emoji_settings
 *
 * @since 1.0.7
 */
function cw_emoji_settings() {

	//* Cache the class. Do not run everything more than once.
	static $cw_emoji_settings = null;

	/**
	 * Applies filters 'cw_emoji_settings_load' : bool Whether to load this plugin.
	 */
	if ( apply_filters( 'cw_emoji_settings_load', true ) )
		if ( ! isset( $cw_emoji_settings ) )
			$cw_emoji_settings = new Emoji_Settings_Field();

	return $cw_emoji_settings;
}

/**
 * Emoji Settings class
 *
 * @since 1.0.0
 */
class Emoji_Settings_Field {

	/**
	 * Settings array, providing defaults.
	 *
	 * @since 1.0.1
	 *
	 * @var array Holds emoji settings
	 */
	protected $options = array();

	/**
	 * Constructor. Set up defaults and initialize actions.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'admin_init', array( $this, 'register_fields' ) );
		add_action( 'init', array( $this, 'disable_emojis' ), 4 );

		//* Default settings
		$this->options = array(
			'default' => '1',
			'enable'  => false,
			'disable' => false
			);
	}

	/**
	 * Return the compiled options.
	 *
	 * @since 1.0.1
	 *
	 * @param array $options The filterable options
	 *
	 * @return array The Emoji options
	 */
	public function get_option( $options = array() ) {

		/**
		 * Filter the Emoji options.
		 *
		 * @since 1.0.1
		 *
		 * Applies filters : the_emoji_options - Allows change of default emoji options.
		 *
		 * @param array $options {
		 *      Arguments for Emoji settings.
		 *
		 *      @type string 	$default		Turn global emoji output on or off by default before settings applied.
		 *      @type bool 		$enable			Override the settings and turn the emojis on anyway.
		 *      @type bool 		$disable		Override the settings and turn the emojis off anyway.
		 * }
		 */
		$options = (array) apply_filters( 'the_emoji_options', wp_parse_args( $options, $this->options ) );

		return $options;
	}

	/**
	 * Sanitize the options.
	 * Prevents wrong filters.
	 *
	 * @since 1.0.1
	 *
	 * @staticvar null|array $option_cached
	 *
	 * @return array Sanitized the emoji options
	 */
	protected function option() {

		static $option_cached = null;

		if ( isset( $option_cached ) )
			return $option_cached;

		$options = $this->get_option();

		//* Cache default in var.
		$default = $options['default'];

		//* Cast $default to one or zero.
		if ( '1' !== $default && '0' !== $default ) {
			if ( $default ) {
				$options['default'] = '1';
			} else {
				$options['default'] = '0';
			}
		}

		//* Cast 'enable' & 'disable' to bool.
		$options['enable'] = (bool) $options['enable'];
		$options['disable'] = (bool) $options['disable'];

		return $option_cached = $options;
	}

	/**
	 * Add new fields to wp-admin/options-writing.php page
	 *
	 * @since 1.0.0
	 */
	public function register_fields() {

		register_setting( 'writing', 'enable_emoji', array( $this, 'wp430_support' ) );

		add_settings_field(
			'enable_emoji',
			__( 'Emoji Support', 'emoji-settings'),
			array( $this, 'fields_html' ),
			'writing'
		);

	}

	/**
	 * HTML output for settings
	 *
	 * @since 1.0.0
	 */
	public function fields_html() {

		$option = $this->option();
		$enable = get_option( 'enable_emoji', $option['default'] );

		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Emoji Support', 'emoji-settings' ) ?></span></legend>
			<label for="enable_emoji">
				<input name="enable_emoji" type="checkbox" id="enable_emoji" value="1" <?php checked( '1', $enable ); ?> />
				<?php _e( 'Enable emoji support', 'emoji-settings' ) ?>
			</label>
		</fieldset>
		<?php


	}

	/**
	 * Adds emoji disable support filter for WP 4.3.0
	 * Filter the options. Disables use_smilies on old WordPress installations if enable emojis is disabled.
	 * This Prevents unreadable character output caused by 'use_smilies' option.
	 *
	 * @param array $options The options POST.
	 *
	 * @since 1.0.5
	 *
	 * @return array $options The options to save.
	 */
	public function wp430_support( $options ) {

		if ( ( ! isset( $_POST['enable_emoji'] ) || '1' !== $_POST['enable_emoji'] ) && isset( $_POST['use_smilies'] ) && '1' === $_POST['use_smilies'] ) {
			if ( get_site_option( 'initial_db_version' ) < 32453 ) {
				update_option( 'use_smilies', '0' );
			}
		}

		return $options;
	}

	/**
	 * Disable the emoji output based on option
	 *
	 * @since 1.0.0
	 *
	 * @uses disable_emojis_tinymce
	 */
	public function disable_emojis( $options = array() ) {

		$option = $this->option();

		/**
		 * Default the option to true if it's a new blog or the option page of the
		 * blog hasn't been visited yet when this plugin has been activated so
		 * this doesn't undesireably prevent/'unprevent' the emojis from being output.
		 */
		$default = get_option( 'enable_emoji', $option['default'] );

		/**
		 * If the emoji settings is set to off:	remove the emoji scripts and other settings.
		 *
		 * If the enable value is set to true: 	Keep the emoji scripts output.
		 * If the disable value is set to true: Remove the emoji scripts output.
		 *
		 * @since 1.0.0
		 */
		if ( $option['disable'] || ( ! $option['enable'] && '1' !== $default ) ) {
			/*
			 * @credits https://wordpress.org/plugins/disable-emojis/
			 */
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Front-end browser support detection script
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); // Admin browser support detection script
			remove_action( 'wp_print_styles', 'print_emoji_styles' ); // Emoji styles
			remove_action( 'admin_print_styles', 'print_emoji_styles' ); // Admin emoji styles
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' ); // Remove from feed, this is bad behaviour!
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); // Remove from feed, this is bad behaviour!
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' ); // Remove from mail
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) ); // Remove from tinymce

			if ( get_site_option( 'initial_db_version' ) >= 32453 ) {
				remove_action( 'init', 'smilies_init', 5 ); // This removes the ascii to smiley convertion
			}
		}

	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @since 1.0.0
	 *
	 * @credits https://wordpress.org/plugins/disable-emojis/
	 *
	 * @param    array  $plugins
	 * @return   array	Difference betwen the two arrays
	 */
	public function disable_emojis_tinymce( $plugins ) {

		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}

	}

}
