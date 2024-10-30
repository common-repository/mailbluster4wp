<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MailBluster4WP_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $mailbluster4wp    The string used to uniquely identify this plugin.
	 */
	protected $mailbluster4wp;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The custom post type of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $version    The custom post type of the plugin.
	 */
	public $post_type;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MAILBLUSTER4WP_VERSION' ) ) {
			$this->version = MAILBLUSTER4WP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->mailbluster4wp = 'mailbluster4wp';

		$this->post_type = 'mb4wpform';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MailBluster4WP_Loader. Orchestrates the hooks of the plugin.
	 * - MailBluster4wp_Helper. Defines all helper functions of the plugin.
	 * - MailBluster4WP_i18n. Defines internationalization functionality.
	 * - MailBluster4WP_Form_Option. Defines all meta box in admin area of the plugin.
	 * - MailBluster4WP_Widget. Extend WP_Widget to create MailBluster widgets.
	 * - MailBluster4WP_Admin. Defines all hooks for the admin area.
	 * - MailBluster4WP_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mailbluster4wp-loader.php';

		/**
		 * The class responsible for implementing and calling helper functions of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mailbluster4wp-helper.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mailbluster4wp-i18n.php';

		/**
		 * The class responsible for defining all actions that generate metabox functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mailbluster4wp-form-option.php';

		/**
		 * The class responsible for defining all actions that generate widget functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mailbluster4wp-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mailbluster4wp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mailbluster4wp-public.php';

		$this->loader = new MailBluster4WP_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MailBluster4WP_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MailBluster4WP_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = $this->get_admin_class();
		$plugin_meta_box = new MailBluster4WP_Form_Option();

		$this->loader->add_action( 'init', $plugin_admin, 'register_mailbluster4wp_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_mailbluster4wp_taxonomy' );
		$this->loader->add_action('widgets_init', $plugin_admin, 'register_mailbluster4wp_widget');
		$this->loader->add_action( 'add_meta_boxes_'.$this->post_type , $plugin_meta_box,'register_meta_box' );
		$this->loader->add_action( 'save_post_'.$this->post_type , $plugin_meta_box,'save_meta_box_data',10,1 );
		$this->loader->add_action('edit_form_after_title', $plugin_admin, 'mb4wp_render_meta_box_after_title');
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'mb4wp_append_taxonomy_descriptions_metabox' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mb4wp_cpt_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_action('admin_menu', $plugin_admin,'mb4wp_remove_add_new_submenus');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_api_settings' );
		$this->loader->add_action( 'manage_'.$this->post_type.'_posts_custom_column' , $plugin_admin,'add_custom_column_content', 10, 2 );
		$this->loader->add_action('update_option_mb4wp_api_options', $plugin_admin, 'mb4wp_request_api_by_update_option');
		$this->loader->add_action('admin_init', $plugin_admin, 'mb4wp_request_api_by_update_option');
		$this->loader->add_action('admin_notices', $plugin_admin, 'mb4wp_admin_notices');
		$this->loader->add_action('admin_notices', $plugin_admin, 'mb4wp_admin_notice_on_activation');
		$this->loader->add_action('admin_init', $plugin_admin, 'builder_data_update_on_plugin_update');

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path(__DIR__) . $this->mailbluster4wp . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' .$plugin_basename , $plugin_admin, 'add_action_links' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin,'remove_row_actions', 10);
		$this->loader->add_filter( 'manage_'.$this->post_type.'_posts_columns', $plugin_admin, 'add_custom_columns' ) ;
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'mb4wp_cpt_updated_messages' ) ;

		$this->loader->add_action('wp_ajax_mb4wp_dialog_form_process', $plugin_admin, 'mb4wp_dialog_form_process');
		$this->loader->add_action('admin_notices', $plugin_admin, 'mb4wp_admin_notice_dialog');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MailBluster4WP_Public( $this->get_mailbluster4wp(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('wp_ajax_mb4wp_form_process', $plugin_public, 'mb4wp_form_process');
		$this->loader->add_action('wp_ajax_nopriv_mb4wp_form_process', $plugin_public, 'mb4wp_form_process');

		$this->loader->add_shortcode('mailbluster_form', $plugin_public, 'mb4wp_render_form_shortcode', 11);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_mailbluster4wp() {
		return $this->mailbluster4wp;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MailBluster4WP_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * The name of the custom post type of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The custom post type of the plugin.
	 */
	public function get_mb4wp_post_type() {
		return $this->post_type;
	}

	/**
	 * Get instantiated admin object.
	 *
	 * @since     1.0.0
	 * @return    MailBluster4WP_Admin     The mailbluster admin class instance of the plugin.
	 */
	public function get_admin_class() {
		$plugin_admin = new MailBluster4WP_Admin( $this->get_mailbluster4wp(), $this->get_version(), $this->get_mb4wp_post_type() );
		return $plugin_admin;
	}
}
