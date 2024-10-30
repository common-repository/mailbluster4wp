<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/public
 * @author     Your Name <email@example.com>
 */
class MailBluster4WP_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $mailbluster4wp       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $mailbluster4wp, $version ) {

		$this->mailbluster4wp = $mailbluster4wp;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->mailbluster4wp, plugin_dir_url( __FILE__ ) . 'css/mailbluster4wp-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->mailbluster4wp.'-public', plugin_dir_url( __FILE__ ) . 'js/mailbluster4wp-public.js', array( 'jquery' ), $this->version, false );

		$ajax_data = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'form_nonce' => wp_create_nonce('mb4wp_s_form_nonce'),
		);
		wp_localize_script($this->mailbluster4wp.'-public', 'mb4wpAjaxForm', $ajax_data);

	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function mb4wp_render_form_shortcode( $atts ){
		extract(shortcode_atts( array(
			'id' => ''
		), $atts ));
		$form_title 									=  get_the_title(intval($id));
		$form_description 						=  get_post_meta(intval($id), 'mb4wp_form_description', true);
		$appearance_theme 						=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('theme', intval($id));
		$appearance_background_color 	=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('background_color', intval($id));
		$appearance_color 						=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('text_color', intval($id));
		$appearance_titleTextSize 		=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleTextSize', intval($id));
		$appearance_titleMarginTop 		=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginTop', intval($id));
		$appearance_titleMarginRight 	=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginRight', intval($id));
		$appearance_titleMarginBottom =  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginBottom', intval($id));
		$appearance_titleMarginLeft 	=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginLeft', intval($id));
		$appearance_formPaddingY 			=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('formPaddingY', intval($id));
		$appearance_formPaddingX 			=  MailBluster4WP_Helper::mb4wp_get_appearance_by_key('formPaddingX', intval($id));

		$render_form = MailBluster4WP_Helper::mb4wp_render_form(intval($id));

		if(!$render_form){
			return false;
		}

		ob_start();
		?>
		<div class="mb4wp-form-wrapper">
			<div class="mb4wp-form-custom-class-container <?=
			$appearance_theme === 'light' ? 'mb-theme-light' : '' ?> <?=
			$appearance_theme === 'dark' ? 'mb-theme-dark' : '' ?>" <?=
			$appearance_theme === 'custom' ? "style='background-color: $appearance_background_color;
			color: $appearance_color;
			padding:{$appearance_formPaddingY}px {$appearance_formPaddingX}px;'" : ''
			?>>
			<?php
			if(!empty($form_title)){
			?>
			<h3 <?=
			$appearance_theme === 'custom' ? "style='font-size: {$appearance_titleTextSize}px;
				margin:{$appearance_titleMarginTop}px {$appearance_titleMarginRight}px {$appearance_titleMarginBottom}px {$appearance_titleMarginLeft}px;'" : '' ;?>>
				<?= esc_html($form_title); ?>
			</h3>
			<?php
			}
			if(!empty($form_description)) {
				echo '<p>'.esc_html($form_description).'</p>';
			}
			echo $render_form;
			?>
			</div>
		</div>
		<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}

	/**
	 * POST the data of the form
	 */
	public function mb4wp_form_process() {
		check_ajax_referer('mb4wp_s_form_nonce', 'security');

 		$post_id = sanitize_text_field($_POST['post_id']);
		$post_data_key = 'mb4wp_sform_'.$post_id;

		$post_data = MailBluster4WP_Helper::sanitize_field_array($_POST[$post_data_key]);

		parse_str($post_data, $response);

		$response_email = $response[$post_data_key]['email'];

		if(empty($response_email)) {
			$str = '<div class="alert-danger alert">';
			$str .= esc_html(MailBluster4WP_Helper::mb4wp_get_message_by_key('message_missing_email', $post_id));
			$str .='</div>';

			echo $str;
			wp_die();

		} else if($response_email && !filter_var($response_email, FILTER_VALIDATE_EMAIL)) {
			$str = '<div class="alert-danger alert">';
			$str .= esc_html(MailBluster4WP_Helper::mb4wp_get_message_by_key('message_invalid_email', $post_id));
			$str .='</div>';

			echo $str;
			wp_die();
		}

		$response_data = MailBluster4WP_Helper::mb4wp_process_submitted_form($response, $post_id);

		if ( is_wp_error( $response_data ) ) {
			$error_message = $response_data->get_error_message();
			echo "Something went wrong: $error_message";

		} else {
			$response_code = json_decode(wp_remote_retrieve_response_code($response_data));
			if($response_code == 201 || $response_code == 200) {
				$str = '<div class="alert-success alert">';
				$str .= esc_html(MailBluster4WP_Helper::mb4wp_get_message_by_key('message_success', $post_id));
				$str .='</div>';
			} else {
				$str = '<div class="alert-danger alert">';
				$str .= esc_html(MailBluster4WP_Helper::mb4wp_get_message_by_key('message_unknown', $post_id));
				$str .='</div>';
			}

			echo $str;

		}
		wp_die();
	}

}
