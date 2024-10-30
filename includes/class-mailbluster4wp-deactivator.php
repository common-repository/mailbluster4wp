<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP_Deactivator {

	/**
	 * Plugin deactivation function.
	 *
	 * Render all deactivation related instruction.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_transient('mb4wp_plugin_activated');
	}

}
