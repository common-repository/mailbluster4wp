<?php

/**
 * Provide a shortcode meta box view in admin area's post edit screen of the plugin
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin/partials
 */

$post_id = get_the_ID();
$value = '[mailbluster_form id="' . $post_id . '"]';
?>
<div class-="publish_shortcode">
	<p><?php esc_html_e('Copy this shortcode and paste it into your post, page, or text widget content:', 'mailbluster4wp'); ?></p>
	<div id="shortcode_inputarea">
		<input id="shortcode_input" type="text" readonly class="widefat" value="<?php echo esc_attr($value); ?>">
		<button  data-clipboard-text="<?php echo esc_attr($value) ?>" id="copy-btn">Copy</button>
	</div>
</div>