<?php
$messages = get_post_meta(intval(get_the_ID()), 'mb4wp_form_message_options', true);
$helper_class_instance = new MailBluster4WP_Helper();
$default_messages = $helper_class_instance->mb4wp_message_options_field_default_texts();
?>
<div class="mb4wp-container hidden messages inside">
	<table class="form-table">
		<tbody>
			<tr class="mb4wp-text-inputs">
				<th scope="row">
					<label for="mb4wp-form-submit-message"><?php esc_html_e('Submit Button', 'mailbluster4wp') ?></label>
				</th>
				<td>
					<input
						type="text"
						id="mb4wp-form-submit-message"
						class="regular-text"
						name="mb4wp_form_message_options[message_submit]"
						value="<?= isset($messages['message_submit']) ? esc_attr($messages['message_submit']) : $default_messages['message_submit']; ?>"
					>
				</td>
			</tr>
			<tr class="mb4wp-text-inputs">
				<th scope="row">
					<label for="mb4wp-message-success"><?php esc_html_e('Success Message', 'mailbluster4wp') ?></label>
				</th>
				<td>
					<textarea
						id="mb4wp-message-success"
						class="regular-text"
						name="mb4wp_form_message_options[message_success]"
					>
					<?= isset($messages['message_success']) ? esc_attr($messages['message_success']) : $default_messages['message_success']; ?>
					</textarea>
				</td>
			</tr>
			<tr class="mb4wp-text-inputs">
				<th scope="row">
					<label for="mb4wp-missing-email"><?php esc_html_e('Missing Email Error', 'mailbluster4wp') ?></label>
				</th>
				<td>
					<textarea
						id="mb4wp-missing-email"
						class="regular-text"
						name="mb4wp_form_message_options[message_missing_email]"
					>
					<?= isset($messages['message_missing_email']) ? esc_attr($messages['message_missing_email']) : $default_messages['message_missing_email']; ?>
					</textarea>
				</td>
			</tr>
			<tr class="mb4wp-text-inputs">
				<th scope="row">
					<label for="mb4wp-invalid-email"><?php esc_html_e('Invalid Email Error', 'mailbluster4wp') ?></label>
				</th>
				<td>
					<textarea
						id="mb4wp-invalid-email"
						class="regular-text"
						name="mb4wp_form_message_options[message_invalid_email]"
					>
					<?= isset($messages['message_invalid_email']) ? esc_attr($messages['message_invalid_email']) : $default_messages['message_invalid_email']; ?>
					</textarea>
				</td>
			</tr>
			<tr class="mb4wp-text-inputs">
				<th scope="row">
					<label for="mb4wp-header-unknown"><?php esc_html_e('Unknown Error', 'mailbluster4wp'); ?></label>
				</th>
				<td>
					<textarea
						id="mb4wp-header-unknown"
						class="regular-text"
						name="mb4wp_form_message_options[message_unknown]"
					>
					<?= isset($messages['message_unknown']) ? esc_attr($messages['message_unknown']) : $default_messages['message_unknown']; ?>
					</textarea>
				</td>
			</tr>
		</tbody>
	</table>
</div>