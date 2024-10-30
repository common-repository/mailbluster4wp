<?php

/**
 * Provide a description meta box view in admin area's post edit screen of the plugin
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin/partials
 */

$post_id = intval(get_the_ID());
$value = get_post_meta($post_id, 'mb4wp_form_description', true);
$label_txt = esc_html__('Add description', 'mailbluster4wp');
?>
<div id="titlediv">
    <div id="titlewrap">
        <label class="screen-reader-text" for="mb4wp-description"><?php echo esc_html($label_txt); ?></label>
        <textarea rows="6" cols="50" name="mb4wp_form_description" id="mb4wp-description" class="mb4wp-post-description large-text" placeholder="<?php echo esc_attr($label_txt); ?>"><?php echo !empty($value) ? esc_attr($value) : '' ?></textarea>
    </div>
</div>