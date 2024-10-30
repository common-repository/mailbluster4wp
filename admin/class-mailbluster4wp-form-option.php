<?php

/**
 * The mailbluster form option meta box class.
 *
 * This is used to define all meta box render in plugin post screen.
 *
 *
 *
 * @since      1.0.0
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP_Form_Option
{

	/**
	 * Register all form related meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function register_meta_box()
	{

		// Form description meta box.
		add_meta_box(
			'mb4wp_form_description',
			esc_html__('Description', 'mailbluster4wp'),
			array($this, 'render_description_meta_box'),
			'mb4wpform',
			'after_title',
			'high'
		);

		// Form shortcode placeholder meta box.
		add_meta_box(
			'mb4wp_form_shortcode',
			esc_html__('MailBluster Shortcode', 'mailbluster4wp'),
			array($this, 'render_shortcode_meta_box'),
			'mb4wpform',
			'after_title',
			'high'
		);

		// Form option meta box.
		add_meta_box(
			'mb4wp_form_options',
			esc_html__('MailBluster Form Option', 'mailbluster4wp'),
			array($this, 'render_form_option_meta_box'),
			'mb4wpform',
			'normal',
			'high'
		);
	}

	/**
	 * Render description meta box.
	 *
	 * @since 1.0.0
	 */
	public function render_description_meta_box()
	{
		include_once 'partials/mailbluster4wp-form-description.php';
	}

	/**
	 * Render shorcode meta box.
	 *
	 * @since 1.0.0
	 */
	public function render_shortcode_meta_box()
	{
		include_once 'partials/mailbluster4wp-form-admin-shortcode.php';
	}

	/**
	 * Render form option meta box.
	 *
	 * @since 1.0.0
	 */
	public function render_form_option_meta_box()
	{
		include_once 'partials/mailbluster4wp-form-option.php';
	}

	/**
	 * Save meta box data.
	 *
	 * @param $post_id
	 *
	 * @since 1.0.0
	 */
	public function save_meta_box_data($post_id)
	{

		/* If we're not working with a 'post' post type or the user doesn't have permission to save,
		 * then we exit the function.
		 */
		if (!$this->user_can_save($post_id, 'mb4wp_form_options_action', 'mb4wp_form_options_nonce')) {
			return;
		}

		if ($this->value_exists('mb4wp_form_description')) {
			update_post_meta(
				$post_id,
				'mb4wp_form_description',
				sanitize_textarea_field($_POST['mb4wp_form_description'])
			);
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_description');
		}

		if ($this->value_exists('mb4wp_form_builder_options')) {
			update_post_meta(
				$post_id,
				'mb4wp_form_builder_options',
				MailBluster4WP_Helper::sanitize_field_array($_POST['mb4wp_form_builder_options'])
			);
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_builder_options');
		}

		if ($this->value_exists('mb4wp_form_builder_default_label')) {
			update_post_meta(
				$post_id,
				'mb4wp_form_builder_default_label',
				MailBluster4WP_Helper::sanitize_field_array($_POST['mb4wp_form_builder_default_label'])
			);
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_builder_default_label');
		}

		if ($this->value_exists('mb4wp_form_message_options')) {
			update_post_meta(
				$post_id,
				'mb4wp_form_message_options',
				MailBluster4WP_Helper::sanitize_field_array($_POST['mb4wp_form_message_options'])
			);
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_message_options');
		}

		if ($this->value_exists('mb4wp_form_appearance_options')) {

			update_post_meta(
				$post_id,
				'mb4wp_form_appearance_options',
				MailBluster4WP_Helper::sanitize_field_array($_POST['mb4wp_form_appearance_options'])
			);
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_appearance_options');
		}

		if ($this->value_exists('mb4wp_form_settings_options')) {
			$settings_options = $_POST['mb4wp_form_settings_options'];
			$consent_textarea = $settings_options['consent_textarea'];
			unset($settings_options['consent_textarea']);
			
			$sanitized = MailBluster4WP_Helper::sanitize_field_array($settings_options);
			$sanitized['consent_textarea'] = $consent_textarea;
			sprintf(html_entity_decode($consent_textarea));
			// die;

			if (isset($sanitized["consent_checkbox"])) {
				$sanitized["consent_textarea"] = empty($sanitized["consent_textarea"]) ? MailBluster4WP_Helper::consent_default_textarea() : $sanitized["consent_textarea"];
			} else {
				unset($sanitized["consent_textarea"]);
			}
			// Remove all illegal characters from a url
			$url = filter_var($sanitized["redirectURL_textarea"], FILTER_SANITIZE_URL);
			// redirect url and textarea dependent one another for data saving
			if(!isset($sanitized["redirectURL"]) || empty($sanitized["redirectURL_textarea"]) || filter_var($url, FILTER_VALIDATE_URL) === false){
				unset($sanitized["redirectURL"]);
				unset($sanitized["redirectURL_textarea"]);
			}
			
			if (!empty($sanitized)) {
				update_post_meta(
					$post_id,
					'mb4wp_form_settings_options',
					$sanitized
				);
			} else {
				$this->delete_post_meta($post_id, 'mb4wp_form_settings_options');
			}
		} else {
			$this->delete_post_meta($post_id, 'mb4wp_form_settings_options');
		}
	}

	/*

	if(!checkbox || !textAreaValue || !isurl) {
		return;
	}

	if(checkbox && textAreaValue) {
		saveToDB()
	}

	*/

	/**
	 * User permission of saving data.
	 *
	 * @param $post_id
	 * @param $nonce_action
	 * @param $nonce_id
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function user_can_save($post_id, $nonce_action, $nonce_id)
	{

		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST[$nonce_action]) && wp_verify_nonce($_POST[$nonce_action], $nonce_id));

		// Return true if the user is able to save; otherwise, false.
		return !($is_autosave || $is_revision) && $this->is_valid_post_type() && $is_valid_nonce;
	}

	/**
	 * Check the validation of post type.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_valid_post_type()
	{
		return !empty($_POST['post_type']) && 'mb4wpform' === $_POST['post_type'];
	}

	/**
	 * Check value of meta key exists.
	 *
	 * @param $meta_key
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function value_exists($meta_key)
	{
		return !empty($_POST[$meta_key]);
	}

	/**
	 * Delete post meta.
	 *
	 * @param $post_id
	 * @param $meta_key
	 *
	 * @since 1.0.0
	 */
	private function delete_post_meta($post_id, $meta_key)
	{

		if ('' !== get_post_meta($post_id, $meta_key, true)) {
			delete_post_meta($post_id, $meta_key);
		}
	}
}
