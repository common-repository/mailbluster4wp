<?php
$post_id = intval(get_the_ID());
$def_label = get_post_meta($post_id, 'mb4wp_form_builder_default_label', true);
$helper_class_instance = new MailBluster4WP_Helper();
$default_label = $helper_class_instance->mb4wp_builder_options_field_default_label();

$form_input_fields = get_post_meta($post_id, 'mb4wp_form_builder_options', true);
$form_input_fields = isset($form_input_fields) && !empty($form_input_fields) ? $form_input_fields : ['email' => (!empty($def_label) ? $def_label['email'] : $default_label['email']) . '*'];

$response = MailBluster4WP_Helper::get_custom_field_response();
$custom_fields = json_decode($response['body']);

$input_field_merge_tag_arr = [];

?>
<div class="mb4wp-container builder">
	<div class="mb4wp-fmbldr-wrapper">
		<div class="mb4wp-fmbldr-option-panel">
			<h3 class="mb4wp-fmbldr-h3"><?php esc_html_e('Option Panel', 'mailbluster4wp'); ?></h3>
			<div class="mb4wp-fmbldr-option-fields">
				<div class="mb4wp-fmbldr-predefined-fields">
					<h4 class="mb4wp-fmbldr-h4" style="margin-bottom: 0.5rem;"><?php esc_html_e('Built-in Fields', 'mailbluster4wp'); ?></h4>
					<div class="mb4wp-default-button">
						<button
							type="button"
							id="mb4wp-builder-email"
							class="mb4wp-field-button"
							value="email" disabled
						><?=
						isset($def_label['email']) ? $def_label['email'] : $default_label['email'];
						?></button>
						<input
							class="mb4wp-field-input"
							type="hidden"
							name="mb4wp_form_builder_default_label[email]"
							value="<?= isset($def_label['email']) ?
							$def_label['email'] : $default_label['email']; ?>"
						>
						<button
							data-fieldLabel="<?= isset($def_label['email']) ?
							$def_label['email'] : $default_label['email']; ?>"
							class="edit_image_container">
							<div class="dashicons dashicons-edit edit_image"></div>
						</button>
						<button class="check_image_container">
							<div class="dashicons dashicons-saved edit_image"></div>
						</button>
					</div>
					<div class="mb4wp-default-button">
						<button
							type="button"
							id="mb4wp-builder-first-name"
							class="mb4wp-field-button"
							value="first_name"
							><?=
							isset($def_label['first_name']) ?
							$def_label['first_name'] :
							$default_label['first_name']; ?></button>
						<input
							class="mb4wp-field-input"
							type="hidden"
							name="mb4wp_form_builder_default_label[first_name]"
							value="<?= isset($def_label['first_name']) ?
							$def_label['first_name'] :
							$default_label['first_name']; ?>"
						>    
						<button
							data-fieldLabel="<?= isset($def_label['first_name']) ?
							$def_label['first_name'] :
							$default_label['first_name']; ?>"
							class="edit_image_container">
							<div class="dashicons dashicons-edit edit_image"></div>
						</button>
						<button class="check_image_container">
							<div class="dashicons dashicons-saved edit_image"></div>
						</button>
					</div>
					<div class="mb4wp-default-button">
						<button
							type="button"
							id="mb4wp-builder-last-name"
							class="mb4wp-field-button"
							value="last_name"
						><?=
						isset($def_label['last_name']) ?
						$def_label['last_name'] :
						$default_label['last_name']; ?></button>
						<input
							class="mb4wp-field-input"
							type="hidden"
							name="mb4wp_form_builder_default_label[last_name]"
							value="<?= isset($def_label['last_name']) ?
							$def_label['last_name'] : $default_label['last_name']; ?>">
						<button
							data-fieldLabel="<?= isset($def_label['last_name']) ?
							$def_label['last_name'] : $default_label['last_name']; ?>"
							class="edit_image_container">
							<div class="dashicons dashicons-edit edit_image"></div>
						</button>
						<button class="check_image_container">
							<div class="dashicons dashicons-saved edit_image"></div>
						</button>
					</div>
					<h4 class="mb4wp-fmbldr-h4" style="margin-bottom: 0.5rem;"><?php esc_html_e('Custom Fields', 'mailbluster4wp'); ?></h4>
					<?php
					if (isset($custom_fields->fields) && is_array($custom_fields->fields)) {
						foreach ($custom_fields->fields as $custom_field) {
							array_push($input_field_merge_tag_arr, $custom_field->fieldMergeTag); // to show deprecated notice
					?>
					<div class="mb4wp-predefined-button">
						<button
							type="button"
							class="mb4wp-field-button"
							value="<?= $custom_field->fieldMergeTag; ?>"
						><?= $custom_field->fieldLabel; ?></button>
						<button
							data-id="<?= $custom_field->id; ?>"
							data-fieldLabel="<?= $custom_field->fieldLabel; ?>"
							class="edit_image_container"
						>
							<div class="dashicons dashicons-edit edit_image"></div>
						</button>
					</div>
					<?php
						}
					}
					?>
					<input type="button" id="mb4wp-custom-field-add" class="button button-primary" value="Add New Field"/>
				</div>
			</div>
		</div>
		<div class="mb4wp-fmbldr-preview-panel">
			<h3 class="mb4wp-fmbldr-h3"><?php esc_html_e('Preview Panel', 'mailbluster4wp'); ?></h3>
			<div class="mb4wp-form-container">
				<div class="mb4wp-builder-content">
					<h3 id="mb4wp-builder-title" class="mb4wp-builder-title"><?= esc_html(get_the_title(intval($post_id))); ?></h3>
					<?php
					$form_description = get_post_meta(intval($post_id), 'mb4wp_form_description', true);
					?>
					<p id="mb4wp_builder_description" class="mb4wp-builder-summary"><?= esc_html($form_description) ?></p>
				</div>
				<div id="sortable" class="mb4wp-form">
					<?php
					if (is_array($form_input_fields)) {
						foreach ($form_input_fields as $form_input_field_key => $form_input_field_value) {
					?>
					<div class="mb4wp-fmbldr-dynamic-form-fields">
						<div class="mb4wp-fmbldr-text-inputs">
							<label
								for="<?= esc_attr($form_input_field_key); ?>"
								class="mb4wp-label"><?= $form_input_field_value; ?></label>
							<input
								type="text"
								id="<?= esc_attr($form_input_field_key); ?>"
								class="regular-text"
								disabled
							>
							<input
								type="hidden"
								name="mb4wp_form_builder_options[<?= $form_input_field_key; ?>]"
								value="<?= $form_input_field_value; ?>"
							>
								
							<?php if ($form_input_field_key != 'email') { ?>
							<span class="dashicons dashicons-dismiss mb4wp-fmbldr-close"></span>
							<?php } ?>

							<span class="dashicons dashicons-move"></span>

							<?php
							$input_field_merge_tag_str = implode(',', $input_field_merge_tag_arr);
							if(!str_contains($input_field_merge_tag_str, $form_input_field_key)
								&& $form_input_field_key != 'email'
								&& $form_input_field_key != "first_name"
								&& $form_input_field_key != "last_name"){
							?>

							<p class="mb4wp-fmbldr-notice"><?=
								esc_html_e("This field is missing in your brand, which may cause issues with its functionality.", "mailbluster4wp");
							?></p>

							<?php } ?>
							<p class="required-field">
								<input
									type="checkbox"
									name="<?= esc_attr('required_' . $form_input_field_key); ?>"
									id="<?= esc_attr('required_' . $form_input_field_key); ?>"
									<?= ($form_input_field_key != 'email') ? '' : 'disabled checked' ?>
									<?= ('*' == substr($form_input_field_value, -1)) ? 'checked' : '' ?>
								>
								<label for="<?= esc_attr('required_' . $form_input_field_key); ?>"><?= __('Required field', 'mailbluster4wp')?></label><br>
							</p>
						</div>
					</div>
					<?php
						}
					}
					$show_consent_checkbox = MailBluster4WP_Helper::mb4wp_get_settings_by_key('consent_checkbox', get_the_ID());
					$show_consent_text = MailBluster4WP_Helper::mb4wp_get_settings_by_key('consent_textarea', get_the_ID());
					?>
					<div id="mb4wp_form_builder_consent" class="mb4wp-builder-content" style="display: <?= (isset($show_consent_checkbox) && ($show_consent_checkbox == 'yes')) ? "flex" : "none"; ?>;">
						<input type="checkbox" name="" id="mb4wp_builder_form_chekbox" disabled>
						<label id="mb4wp_builder_form_checkbox_label" for="mb4wp_builder_form_chekbox"><?= (isset($show_consent_checkbox) && ($show_consent_checkbox == 'yes') && !empty($show_consent_text)) ? strip_tags($show_consent_text, "<p>, <u>, <em>, <strong>, <a>") : MailBluster4WP_Helper::consent_default_textarea(); ?></label>
					</div>
				</div>
				<div class="mb4wp-form-preview-submit">
					<?php
					$submit_label = MailBluster4WP_Helper::mb4wp_get_message_by_key('message_submit', get_the_ID());
					$submit_label = (isset($submit_label) && !empty($submit_label)) ? $submit_label : 'Subscribe';
					?>
					<button id="mb4wp_builder_subscribe_btn" disabled><?= esc_html($submit_label); ?></button>
				</div>
				<?php
				$show_branding = MailBluster4WP_Helper::mb4wp_get_settings_by_key('form_branding', get_the_ID());
				$url              = get_site_url();
				$url_data         = parse_url( $url );
				$url_data['host'] = explode( '.', $url_data['host'] );
				
				$urlWithoutSsl = join( '.', $url_data['host'] );
				?>
				<div id="mb4wp_builder_form_branding" class="mb4wp-form-branding" style="display: <?= (isset($show_branding) && ($show_branding == 'yes')) ? "block" : "none"; ?>">
					<?= sprintf(__('Powered by <a href="https://mailbluster.com?utm_source=form&utm_medium=wordpress_plugin&utm_campaign=%s" target="_blank" rel="noopener">MailBluster</a>', 'mailbluster4wp'), $urlWithoutSsl); ?>
				</div>
			</div>
		</div>
	</div>
</div>