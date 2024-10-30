<?php
/**
 * Provide a form builder meta box view in admin area's post edit screen of the plugin
 *
 * This form builder used to build interface of form
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin/partials
 */

 $response = MailBluster4WP_Helper::get_custom_field_response();
 if(is_wp_error($response)){
	$content = '<h3 style="font-weight:normal;">Loading form option...</h3>';
	echo $content;
	return;
 }
?>
<div id="mailbluster-form-options">
    <h2 class="nav-tab-wrapper wp-clearfix mb4wp-form-wrapper">
        <a class="nav-tab mb4wp-nav-tab mb4wp-nav-tab-active" href="#" data-trigger=".builder"><?php esc_html_e('Builder', 'mailbluster4wp'); ?></a>
        <a class="nav-tab mb4wp-nav-tab" href="#" data-trigger=".messages"><?php esc_html_e('Messages', 'mailbluster4wp'); ?></a>
        <a class="nav-tab mb4wp-nav-tab" href="#" data-trigger=".appearance"><?php esc_html_e('Appearance', 'mailbluster4wp'); ?></a>
        <a class="nav-tab mb4wp-nav-tab" href="#" data-trigger=".settings"><?php esc_html_e('Settings', 'mailbluster4wp'); ?></a>
    </h2>
	<?php

	include_once( 'form-options/builder.php' );
	include_once( 'form-options/appearance.php' );
	include_once( 'form-options/messages.php' );
	include_once( 'form-options/settings.php' );

	wp_nonce_field( 'mb4wp_form_options_nonce', 'mb4wp_form_options_action' );

	?>
</div>