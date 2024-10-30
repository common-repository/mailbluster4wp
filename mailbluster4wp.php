<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mailbluster.com
 * @since             1.0.0
 * @package           MailBluster4WP
 *
 * @wordpress-plugin
 * Plugin Name:       MailBluster For WordPress
 * Plugin URI:        https://mailbluster.com
 * Description:       A free and simple WordPress plugin for MailBluster which provides different methods to create and include subscription forms into WordPress pages or posts by utilizing AmazonSES service.
 * Version:           2.2.2
 * Tested up to:	  	6.4.3
 * Author:            MailBluster
 * Author URI:        https://mailbluster.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mailbluster4wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MAILBLUSTER4WP_VERSION', '2.2.2');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mailbluster4wp-activator.php
 */
function activate_mailbluster4wp()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-mailbluster4wp-activator.php';
	MailBluster4WP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mailbluster4wp-deactivator.php
 */
function deactivate_mailbluster4wp()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-mailbluster4wp-deactivator.php';
	MailBluster4WP_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_mailbluster4wp');
register_deactivation_hook(__FILE__, 'deactivate_mailbluster4wp');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-mailbluster4wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mailbluster4wp()
{

	$plugin = new MailBluster4WP();
	$plugin->run();
}
run_mailbluster4wp();
