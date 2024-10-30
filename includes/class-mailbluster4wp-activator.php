<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP_Activator {

	/**
	 * Plugin activation function.
	 *
	 * Render all activation related instruction.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		set_transient('mb4wp_plugin_activated', true);
		self::mb4wp_set_default_option_value();
		self::rewrite_flush();
	}

	/**
	 * rewrite flush of custom post type.
	 *
	 * @since 1.0.0
	 */
	private static function rewrite_flush() {

		$mailbluster_main = new MailBluster4WP();
		$mailbluster_admin = $mailbluster_main->get_admin_class();
		$mailbluster_admin->register_mailbluster4wp_post_type();
		flush_rewrite_rules();

	}

	/**
	 * Set default option value.
	 *
	 * @since 1.0.0
	 */
	private static function mb4wp_set_default_option_value() {
		$api_options = array(
			'mb4wp_api_key' => '',
			'mb4wp_api_meta' => esc_html__('disconnected, apiKey field is required', 'mailbluster4wp')
		);
		add_option('mb4wp_api_options', $api_options);
	}


}
