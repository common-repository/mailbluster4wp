<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $mailbluster4wp    The ID of this plugin.
	 */
	private $mailbluster4wp;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * A reference to the meta box.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      MailBluster4WP_Form_Option    $meta_box    A reference to the meta box for the plugin.
	 */
	private $meta_box;

	/**
	 * The MailBluster plugin custom post type
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     object      $post_type    The MailBluster plugin custom post type
	 */
	private $post_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $mailbluster4wp       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $post_type    The post_type of this plugin.
	 */
	public function __construct($mailbluster4wp, $version, $post_type)
	{

		$this->mailbluster4wp = $mailbluster4wp;
		$this->version = $version;
		$this->post_type = $post_type;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MailBluster4WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MailBluster4WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			'quillcss',
			'https://cdn.quilljs.com/1.3.6/quill.snow.css',
			array(),
			'1.3.6',
			'all'
		);

		wp_enqueue_style(
			$this->mailbluster4wp,
			plugin_dir_url(__FILE__) . 'css/mailbluster4wp-admin.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MailBluster4WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MailBluster4WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script(
			'quilljs',
			'https://cdn.quilljs.com/1.3.6/quill.js',
			[],
			'1.3.6',
			true
		);

		wp_enqueue_script(
			$this->mailbluster4wp,
			plugin_dir_url(__FILE__) . 'js/mailbluster4wp-admin.js',
			array('jquery'),
			$this->version,
			false
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param $hook_suffix
	 *
	 * @since    1.0.0
	 */
	public function mb4wp_cpt_enqueue_scripts($hook_suffix)
	{

		if (in_array($hook_suffix, array('post.php', 'post-new.php'))) {
			$screen = get_current_screen();

			if (is_object($screen) && $this->get_post_type() == $screen->post_type) {

				wp_enqueue_script(
					$this->mailbluster4wp . '-builder',
					plugin_dir_url(__FILE__) . 'js/mailbluster4wp-form-builder.js',
					array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'),
					$this->version,
					false
				);

				wp_enqueue_script(
					'clipboradjs',
					'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js',
					['jQuery'],
					'1.4.0',
					true
				);

				$ajax_data = array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'form_nonce' => wp_create_nonce( 'mb4wp_dialog_form_nonce' ),
				);
				
				wp_localize_script( $this->mailbluster4wp.'-builder', 'mb4wpAjaxDialogForm', $ajax_data );
			}
		}
	}

	/**
	 * POST/UPDATE/DELETE the data of the custom field
	 */

	public static function mb4wp_dialog_form_process(){

		check_ajax_referer( "mb4wp_dialog_form_nonce", "security" );
		$post_data = MailBluster4WP_Helper::sanitize_field_array($_POST["formData"]);
		parse_str($post_data, $response);

		if($response["mb4wp_dialog_form"]["id"] === ''){
			$method = "POST";
		} else {
			$method = $_REQUEST["method"];
		}
		
		$response_data = MailBluster4WP_Helper::cud_custom_field($response, $method);

		if ( is_wp_error($response_data) ) {
			$error_message = $response_data->get_error_message();
			echo "Something went wrong: $error_message";

		} else {
			$response_code = json_decode(wp_remote_retrieve_response_code($response_data));
			$decoded_response = json_decode($response_data["body"]);

			setcookie("mb4wp-dialog-code", $response_code, time() + (3 * 30), "/");

			echo $decoded_response->message;
			if($decoded_response->message){
				echo '<br/>';
			}
			echo $decoded_response->fieldLabel;
			if($decoded_response->fieldLabel){
				echo '<br/>';
			}
			echo $decoded_response->fieldMergeTag;
		}

		wp_die();
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param $links
	 *
	 * @return array
	 * @since    1.0.0
	 */
	public function add_action_links($links)
	{

		$settings_link = array(
			'<a href="' . esc_url(admin_url('edit.php?post_type=' . $this->post_type)) . '">' . esc_html__('Settings', $this->mailbluster4wp) . '</a>',
		);
		return array_merge($settings_link, $links);
	}

	/**
	 * Register custom post type
	 *
	 * @since 1.0.0
	 */
	public function register_mailbluster4wp_post_type()
	{

		$menu_icon = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.00883 19.9977"><defs><style>.cls-1{fill:#057bde;}</style></defs><title>notcompound</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M14.999,1.34681A9.996,9.996,0,0,0,1.189,14.72694a1.05545,1.05545,0,0,0,1.85.04l1.16-2c0-.01.01-.02.01-.03a.50782.50782,0,0,1,.86.54l-1.75,3.04a1.04455,1.04455,0,0,0,.23,1.32983l.02.02c.05.03.1.07007.15.1001a1.06111,1.06111,0,0,0,1.44-.39014l2.64-4.57007a.03149.03149,0,0,1,.01-.02.51284.51284,0,0,1,.7-.1499.523.523,0,0,1,.16.7002l-2.63,4.5498a1.19449,1.19449,0,0,0-.08.16016,1.05987,1.05987,0,0,0,.63,1.35986l.02.01a1.05969,1.05969,0,0,0,1.27-.47l1.76-3.04c0-.01.01-.02.01-.03a.51043.51043,0,0,1,.86.55005l-1.15,1.99a1.05794,1.05794,0,0,0,.96,1.58008,9.99289,9.99289,0,0,0,4.68-18.65015ZM14.049,16.067a.51206.51206,0,0,1-.50995.50976.554.554,0,0,1-.34-.12988,10.33916,10.33916,0,0,1-3.35-5.3999.51849.51849,0,0,0-.6-.41016.185.185,0,0,1-.07.02,10.39129,10.39129,0,0,1-6.36-.19995.51861.51861,0,0,1-.32-.65015.49228.49228,0,0,1,.23-.27978L13.289,3.4369a.50513.50513,0,0,1,.69.17993.48452.48452,0,0,1,.07.26Z"/></g></g></svg>');
		$labels = array(
			'name'               => esc_html_x('MailBluster', 'mailbluster forms', $this->mailbluster4wp),
			'singular_name'      => esc_html_x('MailBluster', 'mailbluster forms', $this->mailbluster4wp),
			'menu_name'          => esc_html_x('MailBluster', 'admin menu', $this->mailbluster4wp),
			'name_admin_bar'     => esc_html_x('MailBluster', 'add new on admin bar', $this->mailbluster4wp),
			'add_new'            => esc_html_x('Add New', $this->post_type, $this->mailbluster4wp),
			'add_new_item'       => esc_html__('Add New Form', $this->mailbluster4wp),
			'new_item'           => esc_html__('New Form', $this->mailbluster4wp),
			'edit_item'          => esc_html__('Edit Form', $this->mailbluster4wp),
			'view_item'          => esc_html__('View Form', $this->mailbluster4wp),
			'all_items'          => esc_html__('Forms', $this->mailbluster4wp),
			'search_items'       => esc_html__('Search Forms', $this->mailbluster4wp),
			'parent_item_colon'  => esc_html__('Parent Forms:', $this->mailbluster4wp),
			'not_found'          => esc_html__('No forms found.', $this->mailbluster4wp),
			'not_found_in_trash' => esc_html__('No forms found in Trash.', $this->mailbluster4wp)
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__('Description.', $this->mailbluster4wp),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'taxonomies'         => array('mb4wp_tags'),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 85,
			'menu_icon'          => $menu_icon,
			'supports'           => array('title',)
		);

		register_post_type($this->post_type, $args);
	}

	/**
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function mb4wp_cpt_updated_messages($messages)
	{

		$post             = get_post();
		$post_type        = get_post_type($post);
		$post_type_object = get_post_type_object($post_type);
		$revision         = isset($_GET['revision']) ? sanitize_text_field($_GET['revision']) : false;

		$messages[$this->post_type] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => esc_html__('Form updated.', $this->mailbluster4wp),
			2  => esc_html__('Custom field updated.', $this->mailbluster4wp),
			3  => esc_html__('Custom field deleted.', $this->mailbluster4wp),
			4  => esc_html__('Form updated.', $this->mailbluster4wp),
			/* translators: %s: date and time of the revision */
			5  => sprintf(esc_html__('Form restored to revision from %s', $this->mailbluster4wp), wp_post_revision_title((int) $revision, false)),
			6  => esc_html__('Form published.', $this->mailbluster4wp),
			7  => esc_html__('Form saved.', $this->mailbluster4wp),
			8  => esc_html__('Form submitted.', $this->mailbluster4wp),
			9  => sprintf(
				'%1$s<strong>%2$s</strong>',
				esc_html__('Form scheduled for:', $this->mailbluster4wp),
				// translators: Publish box date format, see http://php.net/date
				date_i18n(esc_html__('M j, Y @ G:i', $this->mailbluster4wp), strtotime($post->post_date))
			),
			10 => esc_html__('Form draft updated.', $this->mailbluster4wp)
		);

		if ($post_type_object->publicly_queryable && $this->post_type === $post_type) {
			$permalink = get_permalink($post->ID);

			$view_link = sprintf(' <a href="%1$s">%2$s</a>', esc_url($permalink), esc_html__('View Form', $this->mailbluster4wp));
			$messages[$post_type][1] .= $view_link;
			$messages[$post_type][6] .= $view_link;
			$messages[$post_type][9] .= $view_link;

			$preview_permalink = add_query_arg('preview', 'true', $permalink);
			$preview_link = sprintf(' <a target="_blank" href="%1$s">%2$s</a>', esc_url($preview_permalink), esc_html__('Preview Form', $this->mailbluster4wp));
			$messages[$post_type][8]  .= $preview_link;
			$messages[$post_type][10] .= $preview_link;
		}

		return $messages;
	}

	/**
	 * Register custom taxonomy
	 *
	 * @since 1.0.0
	 */
	public function register_mailbluster4wp_taxonomy()
	{

		$tag_name = 'mb4wp_tags';

		$labels = array(
			'name'               => esc_html_x('MailBluster Tags', 'mailbluster forms', $this->mailbluster4wp),
			'singular_name'      => esc_html_x('MailBluster Tag', 'mailbluster forms', $this->mailbluster4wp),
			'menu_name'          => esc_html_x('MailBluster Tags', 'admin menu', $this->mailbluster4wp),
		);

		$args = array(
			'labels' => $labels,
			'description' => esc_html__('New subscribers will be tagged in MailBluster with these tags.', $this->mailbluster4wp),
			'public' => true,
			'hierarchical' => false,
			'show_ui' => true,
			'show_in_menu'  => false,
			'show_admin_column' => false,
			'query_var' => true,
			'rewrite' => false,
		);

		register_taxonomy($tag_name, array($this->post_type), $args);
	}

	/**
	 * Register widget
	 *
	 * @since 1.0.0
	 */
	public function register_mailbluster4wp_widget()
	{
		register_widget('MailBluster4WP_Widget');
	}


	/**
	 * Add custom columns to wp list
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function add_custom_columns()
	{

		$columns = array(
			'cb' => '&lt;input type="checkbox" />',
			'title' => esc_html__('Form Title', $this->mailbluster4wp),
			'id' => esc_html__('ID', $this->mailbluster4wp),
			'shortcode' => esc_html__('Shortcode', $this->mailbluster4wp),
			'mb4wp_tags'  => esc_html__('MailBluster Tags', $this->mailbluster4wp),
			'date' => esc_html__('Date', $this->mailbluster4wp)
		);

		return $columns;
	}

	/**
	 * Add column content of wp list table
	 *
	 * @param $column_name
	 * @param $post_id
	 */
	public function add_custom_column_content($column_name, $post_id)
	{

		$id = absint($post_id);

		switch ($column_name) {

			case 'id':
				echo $id;
				break;
			case 'mb4wp_tags':
				$term_obj_list = get_the_terms($id, 'mb4wp_tags');
				if (!empty($term_obj_list)) {
					$terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
					echo $terms_string;
				}
				break;
			case 'shortcode':
				echo sprintf('[mailbluster_form id="%s"]', $id);
				break;
		}
	}

	/**
	 * Remove row action
	 *
	 * @since 1.0.0
	 * @param $actions
	 * @return mixed
	 */
	public function remove_row_actions($actions)
	{
		if (get_post_type() === $this->post_type)
			unset($actions['inline hide-if-no-js']);
		// unset( $actions['view'] );
		return $actions;
	}

	/**
	 * Render meta box after tile giving
	 *
	 * @since 1.0.0
	 */
	public function mb4wp_render_meta_box_after_title()
	{

		global $post, $wp_meta_boxes;

		do_meta_boxes(get_current_screen(), 'after_title', $post);

		unset($wp_meta_boxes['post']['after_title']);
	}

	/**
	 * Remove Add new submenus
	 *
	 * @since 1.0.0
	 */
	function mb4wp_remove_add_new_submenus()
	{

		global $submenu;
		$first_index_key = 'edit.php?post_type=' . $this->post_type;
		unset($submenu[$first_index_key][10]);
	}

	/**
	 * Prepend taxonomy descriptions to mailbluster tags taxonomy metabox
	 *
	 * @since 1.0.0
	 *
	 */
	function mb4wp_append_taxonomy_descriptions_metabox()
	{

		$post_types = array($this->post_type);
		$screen     = get_current_screen();

		if ('edit' !== $screen->parent_base) {
			return;
		}

		if (in_array($screen->post_type, $post_types)) {
			$taxonomies = get_object_taxonomies($screen->post_type, 'objects');

			if (!empty($taxonomies)) : ?>

				<script type="text/javascript">
					<?php foreach ($taxonomies as $taxonomy) : ?>

						var tax_slug = '<?php echo $taxonomy->name; ?>';
						var tax_desc = '<?php echo $taxonomy->description; ?>';

						jQuery('#tagsdiv-' + tax_slug + ' div.inside').prepend('<p class="howto">' + tax_desc + '</p>');

					<?php endforeach; ?>
				</script>

				<?php endif;
		}
	}

	/**
	 * Add the admin menu of mailbluster
	 *
	 * @since   1.0.0
	 */
	public function add_admin_menu()
	{
		add_submenu_page(
			'edit.php?post_type=' . $this->post_type,
			esc_html__('MailBluster API Key', $this->mailbluster4wp),
			esc_html__('API Key', $this->mailbluster4wp),
			'manage_options',
			$this->post_type . '-api',
			array($this, 'display_api_screen')
		);
	}

	/**
	 * Register Setting For API Page
	 *
	 * @since   1.0.0
	 */
	public function register_api_settings()
	{

		register_setting('mb4wp_api', 'mb4wp_api_options');

		$message = MailBluster4WP_Helper::mb4wp_get_response_message();

		add_settings_section(
			'mb4wp_api_section',
			'',
			'',
			'mb4wp_api'
		);

		add_settings_field(
			'mb4wp_api_key',
			esc_html__('API Key', $this->mailbluster4wp),
			array($this, 'render_api_key'),
			'mb4wp_api',
			'mb4wp_api_section'
		);

		add_settings_field(
			'mb4wp_api_meta',
			esc_html__('API Status', $this->mailbluster4wp),
			array($this, 'render_api_status'),
			'mb4wp_api',
			'mb4wp_api_section'
		);

		if (is_array($message)) {
			add_settings_field(
				'mb4wp_api_brand_name',
				esc_html__('Brand Name', $this->mailbluster4wp),
				array($this, 'render_brand_name'),
				'mb4wp_api',
				'mb4wp_api_section'
			);

			add_settings_field(
				'mb4wp_api_self_name',
				esc_html__('API Name', $this->mailbluster4wp),
				array($this, 'render_api_name'),
				'mb4wp_api',
				'mb4wp_api_section'
			);
		}
	}

	/**
	 * Render API Key Field
	 *
	 * @since   1.0.0
	 */
	public function render_api_key()
	{
		// Check API key is set and not empty
		$api_key = MailBluster4WP_Helper::mb4wp_isset_not_empty_option_key('mb4wp_api_key');
		echo sprintf(
			'<input type="text" id="mb4wp_api_key" name="mb4wp_api_options[mb4wp_api_key]" value="%s" class="regular-text" pattern="[a-fA-F\-0-9]+" oninvalid="this.setCustomValidity(\'%s\')" oninput="this.setCustomValidity(\'\')" >',
			esc_attr($api_key),
			esc_html__('Invalid API key format', $this->mailbluster4wp)
		);
	}

	/**
	 * Request API key on update
	 *
	 * @since 1.0.0
	 */
	public function mb4wp_request_api_by_update_option()
	{
		MailBluster4WP_Helper::request_api_key();
	}

	/**
	 * Render API status
	 *
	 * @since   1.0.0
	 */
	public function render_api_status()
	{

		// Get API response status
		$api_status = esc_attr(MailBluster4WP_Helper::mb4wp_get_response_status());
		if ($api_status === 'active') {
			echo '<p class="isa_success regular-text">' . esc_html__('Active', $this->mailbluster4wp) . '</p>';
		} elseif ($api_status === 'inactive') {
			echo '<p class="isa_error regular-text">' . esc_html__('Inactive', $this->mailbluster4wp) . '</p>';

			$configure_url = 'https://app.mailbluster.com/auth/signin';
			$link          = sprintf(wp_kses(__('Active your<a href="%s" target="_blank"> MailBluster app brand API key </a> to connect.', $this->mailbluster4wp), array('a' => array('href' => array(), 'target' => array()))), esc_url($configure_url));

			echo '<p class="description">' . $link . '</p>';
		} else {
			echo '<p class="isa_error regular-text">' . esc_html__('Disconnected', $this->mailbluster4wp) . '</p>';

			$configure_url = 'https://app.mailbluster.com/auth/signin';
			$link          = sprintf(wp_kses(__('Input your<a href="%s" target="_blank"> MailBluster app brand API key </a> to connect.', $this->mailbluster4wp), array('a' => array('href' => array(), 'target' => array()))), esc_url($configure_url));

			echo '<p class="description">' . $link . '</p>';
		}
	}

	/**
	 * Render API Brand Name
	 *
	 * @since   1.1.0
	 */
	public function render_brand_name()
	{
		// Get response messages
		$message = MailBluster4WP_Helper::mb4wp_get_response_message();
		if (is_array($message)) {
			$brand_name = $message[1];

			echo '<p class="isa_info regular-text">' . $brand_name . '</p>';
		}
	}

	/**
	 * Render API Name
	 *
	 * @since   1.1.0
	 */
	public function render_api_name()
	{
		// Get response messages
		$message = MailBluster4WP_Helper::mb4wp_get_response_message();
		if (is_array($message)) {
			$api_name = $message[0];

			echo '<p class="isa_info regular-text">' . $api_name . '</p>';
		}
	}

	/**
	 * Admin notice for dialog
	 *
	 *@since 1.0.0
	 */
	public function mb4wp_admin_notice_dialog(){
		global $pagenow;

		if (in_array($pagenow, array('post.php', 'post-new.php'))) {
			$screen = get_current_screen();

			if (is_object($screen) && $this->get_post_type() == $screen->post_type && isset($_COOKIE["mb4wp-dialog-msg"])) {
				if( 200 <= $_COOKIE["mb4wp-dialog-code"] && $_COOKIE["mb4wp-dialog-code"] < 300 ){
					$class = "notice-success";
				} else if ( 400 <= $_COOKIE["mb4wp-dialog-code"] && $_COOKIE["mb4wp-dialog-code"] < 500 ){
					$class = "notice-error";
				} else {
					$class = "notice-warning";
				}
				?>
				<div class="notice is-dismissible <?php echo $class; ?>">
					<p><?php echo $_COOKIE["mb4wp-dialog-msg"]; ?></p>
				</div>
				<?php
			}
		}
	}

	/**
	 * Admin notice for mailbluster
	 *
	 *@since 1.0.0
	 */
	public function mb4wp_admin_notices()
	{
		$screen = get_current_screen();
		$api_page_id = $this->post_type . '_page_' . $this->post_type . '-api';
		$accepted_array = array($api_page_id);
		if (is_object($screen) && in_array($screen->base, $accepted_array)) {
			$api_key  = MailBluster4WP_Helper::mb4wp_get_valid_api_key();
			if ($api_key) {
				if (get_transient('mb4wp_api_connected')) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php esc_html_e('API key connected!', $this->mailbluster4wp); ?></p>
					</div>
				<?php
					delete_transient('mb4wp_api_connected');
				}
			} else {
				if (get_transient('mb4wp_api_disconnected')) {
				?>
					<div class="notice notice-error is-dismissible">
						<p><?php esc_html_e('Enter Correct API key!', $this->mailbluster4wp); ?></p>
					</div>
				<?php
					delete_transient('mb4wp_api_disconnected');
				}
			}
		}
	}


	/**
	 * Admin notice for mailbluster on activation
	 *
	 * @since 1.0.0
	 */
	public function mb4wp_admin_notice_on_activation()
	{
		$screen = get_current_screen();
		$edit_post_type = 'edit-' . $this->post_type;
		$api_page_id = $this->post_type . '_page_' . $this->post_type . '-api';
		$accepted_array_of_id = array('dashboard', $api_page_id, 'plugins', $edit_post_type);
		if (is_object($screen) && in_array($screen->id, $accepted_array_of_id)) {
			$api_key  = MailBluster4WP_Helper::mb4wp_get_valid_api_key();
			if (get_transient('mb4wp_plugin_activated')) {
				if ($api_key) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php esc_html_e('API has been configured', $this->mailbluster4wp); ?></p>
					</div>
				<?php
					delete_transient('mb4wp_plugin_activated');
				} else {
				?>
					<div class="notice notice-error is-dismissible">
						<?php
						$configure_url = 'edit.php?post_type=' . $this->post_type . '&page=' . $this->post_type . '-api';
						$link =  sprintf(wp_kses(__('Please Configure <a href="%s">MailBluster API</a> key.', $this->mailbluster4wp), array('a' => array('href' => array()))), esc_url($configure_url));
						?>
						<p><?php echo $link; ?></p>
					</div>
				<?php
				}
			}
		}
	}

	/**
	 * Render the menu page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_api_screen()
	{

		include_once 'partials/mailbluster4wp-admin-api-display.php';
	}

	/**
	 * Form builder data update after plugin update to the version 2.2.1
	 *
	 * @since 2.2.1
	 */
	public function builder_data_update_on_plugin_update()
	{
		MailBluster4WP_Helper::builder_data_change_on_update();
	}

	/**
	 * Get plugin unique id.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_mailbluster4wp()
	{

		return $this->mailbluster4wp;
	}

	/**
	 * Get plugin version.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_version()
	{

		return $this->version;
	}

	/**
	 * Get plugin custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function get_post_type()
	{

		return $this->post_type;
	}

	/**
	 * Get plugin form-option meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return MailBluster4WP_Form_Option
	 */
	public function get_meta_box()
	{

		$this->meta_box = new MailBluster4WP_Form_Option();
		return $this->meta_box;
	}
}
