<?php

/**
 * Implement all helper function for the plugin
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/includes
 */

class MailBluster4WP_Helper
{
	public static function consent_default_textarea()
	{
		return
			__("I consent to receive your newsletter, occasional offers and marketing emails.", "mailbluster4wp");
	}

	/**
	 * Request API Key.
	 *
	 * @since 1.0.0
	 *
	 */
	public static function request_api_key()
	{

		// Get API options
		$options = get_option('mb4wp_api_options');

		// Check API
		$api_key = self::mb4wp_isset_not_empty_option_key('mb4wp_api_key');

		// Set API key API url
		$url = 'https://api.mailbluster.com/api/api-keys';

		// Prepare request body
		$request_body = array(
			'apiKey' => $api_key
		);

		// Get response by hitting API url
		$response = wp_remote_post(esc_url_raw($url), array(
			'body' => $request_body
		));

		// Prepare and format response body according to response
		$api_status = 'disconnected';
		if (!is_wp_error($response)) {

			// Retrieve json decoded response body
			$response_body = json_decode(wp_remote_retrieve_body($response));
			$response_valid = isset($response_body->name) ? sanitize_text_field($response_body->name) : '';
			$response_invalid = isset($response_body->message) ? sanitize_text_field($response_body->message) : '';
			$response_key_required = isset($response_body->apiKey) ? sanitize_text_field($response_body->apiKey) : '';

			if (!empty($response_valid)) {
				// Get response status
				$response_status = $response_body->status;

				// Get brand name
				$brand_name = sanitize_text_field($response_body->brand->name);

				// Set API status
				$api_status = ($response_status) ? 'active' : 'inactive';

				// Set messages
				$message_body = $response_valid . '|' . $brand_name;
				set_transient('mb4wp_api_connected', 'true');
			} else {
				if (!empty($response_invalid) && empty($response_key_required)) {
					$message_body = $response_invalid;
				} else {
					$message_body = $response_key_required;
				}
				set_transient('mb4wp_api_disconnected', 'true');
				$api_status = 'disconnected';
			}
		} else {
			$message_body = $response->get_error_message();
		}

		$options_meta = $api_status . ', ' . $message_body;

		// Update option with a new key
		$options['mb4wp_api_meta'] = sanitize_text_field($options_meta);
		update_option('mb4wp_api_options', $options);
	}

	/**
	 * Check single option key is set and not empty.
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public static function mb4wp_isset_not_empty_option_key($key)
	{
		$options = get_option('mb4wp_api_options');
		if (isset($options[$key]) && !empty($options[$key])) {
			return sanitize_text_field($options[$key]);
		} else {
			return '';
		}
	}


	/**
	 * Get valid API key.
	 *
	 * @return string
	 */
	public static function mb4wp_get_valid_api_key()
	{

		// Check API key is set and not empty
		$api_key = self::mb4wp_isset_not_empty_option_key('mb4wp_api_key');

		// Get API status
		$api_status = self::mb4wp_get_response_status();
		if ($api_status === 'active' ||
		$api_status === 'inactive') {
			return $api_key;
		} else {
			return '';
		}
	}

	/**
	 * Get API response messages.
	 *
	 * @return array
	 */
	public static function mb4wp_get_response_message()
	{

		// Check API meta
		$api_meta = self::mb4wp_isset_not_empty_option_key('mb4wp_api_meta');

		// Set messages
		$str_process = explode(',', $api_meta);
		$message = $str_process[1];
		if (strpos($message, '|') !== false) {
			$message_process = explode('|', $str_process[1]);
			return $message_process;
		} else {
			return $message;
		}
	}

	/**
	 * Get API response status.
	 *
	 * @return string
	 */
	public static function mb4wp_get_response_status()
	{

		// Check API meta
		$api_meta = self::mb4wp_isset_not_empty_option_key('mb4wp_api_meta');
		if ($api_meta === '') {
			return '';
		}

		// Set API status
		$status = explode(',', $api_meta);
		return $status[0];
	}

	/**
	 * Set all form message options default text
	 *
	 * @return array
	 */
	public function mb4wp_message_options_field_default_texts()
	{
		return array(
			'message_submit' => esc_html__('Subscribe', 'mailbluster4wp'),
			'message_success' => esc_html__('Thanks for subscribing!', 'mailbluster4wp'),
			'message_missing_email' => esc_html__('Your email address is required.', 'mailbluster4wp'),
			'message_invalid_email' => esc_html__('Your email address looks incorrect. Please try again.', 'mailbluster4wp'),
			'message_unknown' => esc_html__('Sorry, an unknown error has occurred. Please try again later.', 'mailbluster4wp'),
		);
	}

	/**
	 * Set all form builder options default label
	 *
	 * @return array
	 */
	public static function mb4wp_builder_options_field_default_label()
	{
		return array(
			'email' => esc_html__('Email Address', 'mailbluster4wp'),
			'first_name' => esc_html__('First Name', 'mailbluster4wp'),
			'last_name' => esc_html__('Last Name', 'mailbluster4wp'),
			'timezone' => esc_html__('Timezone', 'mailbluster4wp'),
		);
	}

	/**
	 * Render MailBluster Form on frontend
	 *
	 * @param $post_id
	 *
	 * @return false|string
	 */
	public static function mb4wp_render_form($post_id)
	{

		// Get API key response status
		$api_status = self::mb4wp_get_response_status();

		// Render form when API active and form published
		if ($api_status === 'active' && 'publish' === get_post_status($post_id)) {
			return self::mb4wp_render_form_html($post_id);
		} else {
			ob_start();
?>
<div class="alert alert-warning">
  <p><?php esc_html_e('Currently the form is not available.', 'mailbluster4wp'); ?></p>
</div>
<?php
			$str = ob_get_clean();
			wp_reset_postdata();
			return $str;
		}
	}

	/**
	 * Render Form html block.
	 *
	 * @param $post_id
	 *
	 * @return false|string
	 */
	private static function mb4wp_render_form_html($post_id)
	{

		// Get and set submit button text
		$submit_text 				= self::mb4wp_get_message_by_key('message_submit', $post_id);

		// Prepare form input detail
		$form_input_detail 	= self::mb4wp_process_form_input_fields($post_id);

		if (empty($form_input_detail)) {
			return false;
		}

		// Get form wrapper class
		$appearance_custom_class 	=  self::mb4wp_get_appearance_by_key('custom_class', $post_id);

		// Get custom theming variable
		$appearance_theme =  self::mb4wp_get_appearance_by_key('theme', $post_id);
		if ('custom' === $appearance_theme) {
			$appearance_color =  self::mb4wp_get_appearance_by_key('text_color', $post_id);
		} else {
			$appearance_color = '';
		}
		$appearance_button_color 			=  self::mb4wp_get_appearance_by_key('button_color', $post_id);
		$appearance_btnText_color 		=  self::mb4wp_get_appearance_by_key('btnText_color', $post_id);
		$appearance_btnPaddingY 			=  self::mb4wp_get_appearance_by_key('btnPaddingY', $post_id);
		$appearance_btnPaddingX 			=  self::mb4wp_get_appearance_by_key('btnPaddingX', $post_id);
		$appearance_btnMarginTop 			=  self::mb4wp_get_appearance_by_key('btnMarginTop', $post_id);
		$appearance_btnMarginRight 		=  self::mb4wp_get_appearance_by_key('btnMarginRight', $post_id);
		$appearance_btnMarginBottom 	=  self::mb4wp_get_appearance_by_key('btnMarginBottom', $post_id);
		$appearance_btnMarginLeft 		=  self::mb4wp_get_appearance_by_key('btnMarginLeft', $post_id);
		$appearance_btnBorder_width 	=  self::mb4wp_get_appearance_by_key('btnBorder_width', $post_id);
		$appearance_btnBorder_color 	=  self::mb4wp_get_appearance_by_key('btnBorder_color', $post_id);
		$appearance_btnBorder_radius 	=  self::mb4wp_get_appearance_by_key('btnBorder_radius', $post_id);
		$appearance_btnText_size 			=  self::mb4wp_get_appearance_by_key('btnText_size', $post_id);
		$appearance_labelTextColor 		=  self::mb4wp_get_appearance_by_key('labelTextColor', $post_id);
		$appearance_fieldPaddingY			 =  self::mb4wp_get_appearance_by_key('fieldPaddingY', $post_id);
		$appearance_fieldPaddingX 		 =  self::mb4wp_get_appearance_by_key('fieldPaddingX', $post_id);

		// Get form branding
		$form_branding = self::mb4wp_get_settings_by_key('form_branding', $post_id);

		// Get form consent
		$show_consent_checkbox 	= self::mb4wp_get_settings_by_key('consent_checkbox', $post_id);
		$show_consent_text 			= self::mb4wp_get_settings_by_key('consent_textarea', $post_id);

		ob_start();
		?>
<div class="mb4wp-form-custom-class-container <?= esc_attr($appearance_custom_class); ?>">
  <form id="mb4wp-s-form_<?= esc_attr($post_id); ?>" class="mb4wp-s-form" method="post">
    <?php
		// Set every input field by input details
		foreach ($form_input_detail as $single_field => $value) {
			self::mb4wp_render_general_input_block($value['id'], $value['placeholder'], $value['name'], $value['required'], $post_id);
		}

		if (isset($show_consent_checkbox) && ($show_consent_checkbox == 'yes')) {
		?>
    <div class="mb4wp-consent-checkbox" <?= $appearance_theme === 'custom' ? "style='padding:{$appearance_fieldPaddingY}px {$appearance_fieldPaddingX}px;'" : ''; ?>>
      <input type="checkbox" id="mb4wp_public_consent_checkbox"><label <?=
				$appearance_theme === 'custom' ? "style='color:$appearance_labelTextColor;'" : ''; ?> 
        for="mb4wp_public_consent_checkbox"><?=
				(isset($show_consent_checkbox) && ($show_consent_checkbox == 'yes') && isset($show_consent_text)) ? strip_tags($show_consent_text, "<p>, <u>, <em>, <strong>, <a>") : self::consent_default_textarea(); ?></label>
    </div>
		<?php
		}
		?>
    <div class="mb4wp-form-group">
      <button <?=
				"data-theme=$appearance_theme"; ?>
				type="submit" id="mb4wp_subscribe"
				class="mb4wp-subscribe" <?=
				$appearance_theme === 'custom' ? "style='
				background-color: $appearance_button_color;
				color:$appearance_btnText_color;
				padding:{$appearance_btnPaddingY}px {$appearance_btnPaddingX}px;
				border:{$appearance_btnBorder_width}px solid $appearance_btnBorder_color;
				border-radius:{$appearance_btnBorder_radius}px;
				margin:{$appearance_btnMarginTop}px {$appearance_btnMarginRight}px {$appearance_btnMarginBottom}px {$appearance_btnMarginLeft}px;
				font-size:{$appearance_btnText_size}px;'" : '' ?>
        <?= (isset($show_consent_checkbox) && ($show_consent_checkbox == 'yes') && isset($show_consent_text) ? "disabled" : ""); ?>><?= esc_html($submit_text); ?></button><input
        type="hidden" class="mb4wp-form-post-id" name="mb4wp_form_post_id" value="<?= esc_attr($post_id); ?>">
    </div>
  </form>

  <div id="mb4wp-form-messages"></div>

  <?php
		if (isset($form_branding) && ($form_branding == 'yes')) {
			$url              = get_site_url();
			$url_data         = parse_url($url);
			$url_data['host'] = explode('.', $url_data['host']);

			$urlWithoutSsl = join('.', $url_data['host']);
	?>
  <div class="mb4wp-form-branding">
    <?php echo sprintf(__('Powered by <a style="color:%s" href="https://mailbluster.com?utm_source=form&utm_medium=wordpress_plugin&utm_campaign=%s" target="_blank" rel="noopener"> MailBluster</a>', 'mailbluster4wp'), $appearance_color, $urlWithoutSsl); ?>
  </div>
	<?php } ?>
</div>
<?php
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}

	/**
	 * Process form input fields.
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	private static function mb4wp_process_form_input_fields($post_id)
	{
		// Get all form fields
		$form_builder_options = get_post_meta($post_id, 'mb4wp_form_builder_options', true);
		if(empty($form_builder_options) || !is_array($form_builder_options)){
			return false;
		}
		$get_builder_options = self::sanitize_field_array($form_builder_options);

		// Set prefix
		$prefix_s = 'mb4wp_sform_';
		$prefix_n_ar = $prefix_s . intval($post_id);

		// Prepare single form fields input attributes
		$form_detailed = [];
		foreach ($get_builder_options as $single_field_key => $single_field_value) {
			$form_detailed[$single_field_key] = array(
				'id' => $prefix_s . $single_field_key,
				'name' => $prefix_n_ar . '[' . $single_field_key . ']',
				'placeholder' => $single_field_value . (('email' === $single_field_key && '*' != substr($single_field_value, -1)) ? '*' : ''),
				'required' => ('*' == substr($single_field_value, -1) || 'email' === $single_field_key) ? 'required' : '',
			);
		}
		return $form_detailed;
	}

	/**
	 * Get the response data of custom field from app.mailbluster.com dashboard
	 *
	 * @return array|WP_Error
	 */
	public static function get_custom_field_response()
	{

		// Set custom field API
		$url = 'https://api.mailbluster.com/api/fields';

		// Get response by hitting API
		$response = wp_safe_remote_get(esc_url_raw($url), array(
			'headers'     => array(
				'Content-Type' => 'application/json',
				'authorization' => self::mb4wp_get_valid_api_key()
			),
		));

		return $response;
	}

	/**
	 * Create(POST) Update(PUT) and Delete the data of custom field from app.mailbluster.com dashboard
	 *
	 * @param $submitted_response (an array of data from the admin dialog submitted form)
	 * @param $method (here post put or delete)
	 * @return array|string|WP_Error
	 */

	public static function cud_custom_field($submitted_response, $method)
	{

		if (isset($submitted_response) && !empty(array_filter($submitted_response))) {
			$submitted_response = $submitted_response["mb4wp_dialog_form"];
			$id = $submitted_response["id"];

			// Set custom field API
			if ($method === "POST") {
				$lead_url = "https://api.mailbluster.com/api/fields";
			} else {
				$lead_url = "https://api.mailbluster.com/api/fields/$id";
			}

			unset($submitted_response["id"]);
			$submitted_response['authorization'] = self::mb4wp_get_valid_api_key();
			$json_data = json_encode($submitted_response);

			// Get response by hitting API
			$response = wp_safe_remote_post(esc_url_raw($lead_url), array(
				'headers'     => array('Content-Type' => 'application/json'),
				'body'        => $json_data,
				'method'      => $method,
				'data_format' => 'body',
			));

			// Return response
			return $response;
		}
		return '';
	}

	/**
	 * Process the data of a submitted form.
	 *
	 * @param $submitted_response
	 * @param $post_id
	 *
	 * @return array|string|WP_Error
	 */
	public static function mb4wp_process_submitted_form($submitted_response, $post_id)
	{

		// Set key
		$key = 'mb4wp_sform_' . intval($post_id);

		// Check response is set and array not empty
		if (isset($submitted_response) && !empty(array_filter($submitted_response))) {

			// Set response by its category
			$form_response = $submitted_response;
			$form_data = $form_response[$key];

			// Prepare, format and sanitize all fields to set request body.
			$request_body = [];
			foreach ($form_data as $single_data => $value) {
				$key = self::mb4wp_make_camel_case($single_data);
				$request_body[$key] = self::mb4wp_sanitize_value_of_key($single_data, $value);
			}

			// Process a single subscription
			return self::mb4wp_process_single_lead($request_body, $post_id);
		} else {
			return '';
		}
	}

	/**
	 * Sanitize fields value by its key.
	 *
	 * @param $field_name
	 * @param $value
	 *
	 * @return string
	 */
	private static function mb4wp_sanitize_value_of_key($field_name, $value)
	{
		switch ($field_name) {
			case 'email':
				$sanitized_value = sanitize_email($value);
				break;
			case 'first_name':
			case 'last_name':
			default:
				$sanitized_value = sanitize_text_field($value);
				break;
		}
		return $sanitized_value;
	}

	/**
	 * Process a single lead/subscription.
	 *
	 * @param $request_body
	 * @param $post_id
	 *
	 * @return array|string|WP_Error
	 */
	private static function mb4wp_process_single_lead($request_body, $post_id)
	{

		$form_builder_options = get_post_meta($post_id, 'mb4wp_form_builder_options', true);
		if (empty($form_builder_options) || !is_array($form_builder_options)) {
			return '';
		}
		$builder_options = self::sanitize_field_array($form_builder_options);

		$required_fields_arr = [];
		foreach ($builder_options as $single_field_key => $single_field_value) {
			if('*' === substr($single_field_value, -1)){
				array_push($required_fields_arr, self::mb4wp_make_camel_case($single_field_key));
			}
		}
		$required_fields = implode(',', $required_fields_arr);

		foreach ($request_body as $key => $value) {
			if(str_contains($required_fields, $key) && empty($value)){
				return;
			}
			if ($key !== 'email' && $key !== 'firstName' && $key !== 'lastName'){
				$request_body['fields'][$key] = $value;
				unset($request_body[$key]);
			}
		}

		$json_data = '';

		// Set lead API
		$lead_url = 'https://api.mailbluster.com/api/leads';

		// Get mailbluster tags
		$term_obj_list = get_the_terms($post_id, 'mb4wp_tags');
		$tags = '';
		if (!empty($term_obj_list)) {
			$terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
			$tags = explode(', ', $terms_string);
		}

		// Filter request body's custom fields array property
		$request_body['fields'] = (object) array_filter((array) $request_body['fields']);

		// Filter request body
		$request_body = array_filter((array) $request_body);

		if (!empty($request_body)) {

			// Get subscriber ip info
			$ip = $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);

			// Set request body for API process
			$request_body['ipAddress'] = $ip;
			$form_doubleOptIn = self::mb4wp_get_settings_by_key('form_doubleOptIn', $post_id);
			if ($form_doubleOptIn) {
				$request_body['doubleOptIn'] = true;
				$request_body['subscribed'] = 'false';
			} else {
				$request_body['subscribed'] = true;
			}
			$request_body['tags'] = $tags;
			$request_body['overrideExisting'] = true;
			$request_body['authorization'] = self::mb4wp_get_valid_api_key();
			// Make object of body data
			$object = (object) array_filter((array) $request_body);

			// Make json data
			$json_data = json_encode($object);

			// Get response by hitting API
			$response = wp_safe_remote_post(esc_url_raw($lead_url), array(
				'headers'     => array('Content-Type' => 'application/json'),
				'body'        => $json_data,
				'method'      => 'POST',
				'data_format' => 'body',
			));
			$redirectUrl = self::mb4wp_get_settings_by_key('redirectURL_textarea', $post_id);
			if ($redirectUrl) {
				echo '<script type="text/javascript">
					window.location = "' . $redirectUrl . '"
		   		</script>';
			}

			// Return response
			return $response;
		}
		return '';
	}

	/**
	 * Render form input block.
	 *
	 * @param $id
	 * @param $placeholder
	 * @param $name
	 */
	private static function mb4wp_render_general_input_block($id, $placeholder, $name, $required, $post_id)
	{
		$appearance_theme 								=  self::mb4wp_get_appearance_by_key('theme', $post_id);
		$appearance_fieldPaddingY 				 =  self::mb4wp_get_appearance_by_key('fieldPaddingY', $post_id);
		$appearance_fieldPaddingX					 =  self::mb4wp_get_appearance_by_key('fieldPaddingX', $post_id);
		$appearance_labelTextSize 				=  self::mb4wp_get_appearance_by_key('labelTextSize', $post_id);
		$appearance_labelTextColor 				=  self::mb4wp_get_appearance_by_key('labelTextColor', $post_id);
		$appearance_inputPadding 					=  self::mb4wp_get_appearance_by_key('inputPadding', $post_id);
		$appearance_inputBorderWidth 			=  self::mb4wp_get_appearance_by_key('inputBorderWidth', $post_id);
		$appearance_inputBorderColor 			=  self::mb4wp_get_appearance_by_key('inputBorderColor', $post_id);
		$appearance_inputBackgroundColor 	=  self::mb4wp_get_appearance_by_key('inputBackgroundColor', $post_id);
		$appearance_inputTextColor 				=  self::mb4wp_get_appearance_by_key('inputTextColor', $post_id);
		$appearance_inputTextColor 				=  self::mb4wp_get_appearance_by_key('inputTextColor', $post_id);
		$appearance_inputBorderRadius 		=  self::mb4wp_get_appearance_by_key('inputBorderRadius', $post_id);
		// Set type and required attribute of a single input field
		if (strpos($id, '_email') !== false) {
			$type = 'email';
			$required = 'required';
		} else {
			$type = 'text';
			$required = $required;
		}

		// Render html block
	?>
<div class="mb4wp-form-group" <?= $appearance_theme === 'custom' ? "style='padding:{$appearance_fieldPaddingY}px {$appearance_fieldPaddingX}px;'" : ''; ?>>
  <label for="<?= esc_attr($id) . '_' . esc_attr($post_id); ?>" class="mb4wp-label"
		<?= $appearance_theme === 'custom' ? "style='font-size:{$appearance_labelTextSize}px; color:$appearance_labelTextColor;'" : '';?>
    ><?= $placeholder; ?></label><input
    type="<?= esc_attr($type); ?>"
		id="<?= esc_attr($id) . '_' . esc_attr($post_id); ?>"
		class="mb4wp-form-control" name="<?= esc_attr($name); ?>" <?= esc_attr($required); ?>
    <?= $appearance_theme === 'custom' ? "style='padding:{$appearance_inputPadding}px;
			border:{$appearance_inputBorderWidth}px solid $appearance_inputBorderColor;
			background-color:$appearance_inputBackgroundColor;
			color:$appearance_inputTextColor;
			border-radius:{$appearance_inputBorderRadius}px;'" : ''; ?>>
</div>
<?php
	}

	/**
	 *
	 * after version 1.2.1 there is a breaking change to save builder options.
	 * if mb4wp_form_builder_options contain string value then this check will make this value in an array format
	 *
	 * @since 2.2.1
	 * 
	 */

	public static function builder_data_change_on_update()
	{

		$default_label = self::mb4wp_builder_options_field_default_label();

		// Get all post IDs
		$post_ids = self::get_all_post_ids();
		// Now you have an array of all post IDs
		foreach ($post_ids as $post_id) {
			$def_label = get_post_meta($post_id, 'mb4wp_form_builder_default_label', true);
			$form_input_fields = get_post_meta($post_id, 'mb4wp_form_builder_options', true);
			$response = self::get_custom_field_response();
			if (!is_wp_error($response)) {
				$custom_fields = json_decode($response['body']);
			}

			if (isset($form_input_fields) && !empty($form_input_fields) && !is_array($form_input_fields)) {
				$data = [];
				if (str_contains($form_input_fields, 'email')) {
					$data['email'] = (!empty($def_label) ? $def_label['email'] : $default_label['email']) . '*';
				}
				if (str_contains($form_input_fields, 'first_name*')) {
					$data['first_name'] = (!empty($def_label) ? $def_label['first_name'] : $default_label['first_name']) . '*';
				} elseif (str_contains($form_input_fields, 'first_name')) {
					$data['first_name'] = !empty($def_label) ? $def_label['first_name'] : $default_label['first_name'];
				}
				if (str_contains($form_input_fields, 'last_name*')) {
					$data['last_name'] = (!empty($def_label) ? $def_label['last_name'] : $default_label['last_name']) . '*';
				} elseif (str_contains($form_input_fields, 'last_name')) {
					$data['last_name'] = !empty($def_label) ? $def_label['last_name'] : $default_label['last_name'];
				}
				if (isset($custom_fields->fields) && is_array($custom_fields->fields)) {
					foreach ($custom_fields->fields as $custom_field) {
						if (str_contains($form_input_fields, $custom_field->fieldLabel . '*')) {
							$data[$custom_field->fieldMergeTag] =
								$custom_field->fieldLabel . '*';
						} elseif (str_contains($form_input_fields, $custom_field->fieldLabel)) {
							$data[$custom_field->fieldMergeTag] =
								$custom_field->fieldLabel;
						}
					}
				}
				update_post_meta(
					$post_id,
					'mb4wp_form_builder_options',
					self::sanitize_field_array($data)
				);
			}
		}
	}

	/**
	 *
	 * Get form messages option by its key.
	 *
	 * @since 2.2.1
	 *
	 * @return array
	 */

	// Function to get all post IDs
	private static function get_all_post_ids()
	{
		$args = array(
			'posts_per_page' => -1, // Get all posts
			'post_type' => 'mb4wpform',
		);

		$posts = get_posts($args);

		$post_ids = [];
		foreach ($posts as $post) {
			$post_ids[] = $post->ID;
		}

		return $post_ids;
	}

	/**
	 *
	 * Get form messages option by its key.
	 *
	 * @param $key
	 * @param $post_id
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function mb4wp_get_message_by_key($key, $post_id)
	{

		// Get message options
		$messages_options = get_post_meta($post_id, 'mb4wp_form_message_options', true);

		// Get message text by key
		$message_text =   isset($messages_options[$key]) ? $messages_options[$key] : '';

		// Return message text
		return !empty($messages_options) ? $message_text : '';
	}

	/**
	 *
	 * Get form appearance option by its key.
	 *
	 * @param $key
	 * @param $post_id
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function mb4wp_get_appearance_by_key($key, $post_id)
	{

		// Get appearance options
		$appearance_options = get_post_meta($post_id, 'mb4wp_form_appearance_options', true);

		// Get appearance text by key
		$appearance_text =   isset($appearance_options[$key]) ? $appearance_options[$key] : '';

		// Return appearance text
		return !empty($appearance_options) ? $appearance_text : '';
	}

	/**
	 *
	 * Get form settings option by its key.
	 *
	 * @param $key
	 * @param $post_id
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function mb4wp_get_settings_by_key($key, $post_id)
	{

		// Get settings options
		$settings_options = get_post_meta($post_id, 'mb4wp_form_settings_options', true);

		// Get settings data by key
		$settings_data =   isset($settings_options[$key]) ? $settings_options[$key] : '';

		// Return settings data
		return !empty($settings_options) ? $settings_data : '';
	}

	/**
	 * Generate beatify print_r for debugging.
	 *
	 * @param $arr
	 *
	 * @since 1.0.0
	 *
	 */
	public static function mb4wp_beautify_print_r($arr)
	{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}


	/**
	 * Make underscore connected word camel case.
	 *
	 * @param $str
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function mb4wp_make_camel_case($str)
	{

		return lcfirst(str_replace('_', '', ucwords($str, '_')));
	}

	/**
	 * Sanitize array field
	 *
	 * @param $array
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public static function sanitize_field_array($array)
	{
		foreach ((array) $array as $k => $v) {
			if (is_array($v)) {
				$array[$k] =  self::sanitize_field_array($v);
			} else {
				$array[$k] = sanitize_text_field($v);
			}
		}
		return $array;
	}
}