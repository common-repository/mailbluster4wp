<?php
$settings                   = get_post_meta(intval(get_the_ID()), 'mb4wp_form_settings_options', true);
$form_doubleOptIn_settings  = MailBluster4WP_Helper::mb4wp_get_settings_by_key('form_doubleOptIn', $post_id);
$form_branding_settings     = MailBluster4WP_Helper::mb4wp_get_settings_by_key('form_branding', $post_id);
$form_consent_settings      = MailBluster4WP_Helper::mb4wp_get_settings_by_key('consent_checkbox', $post_id);
$form_redirectURL_settings  = MailBluster4WP_Helper::mb4wp_get_settings_by_key('redirectURL', $post_id);
$url                        = get_site_url();
?>

<div class="mb4wp-container hidden settings inside">
    <table class="form-table">
        <tbody>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-doubleOptIn"><?php esc_attr_e('Double Opt-in', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="mb4wp-form-doubleOptIn" class="regular-text" name="mb4wp_form_settings_options[form_doubleOptIn]" value="yes" <?php checked($form_doubleOptIn_settings, "yes") ?>>
                    <label for="mb4wp-form-doubleOptIn"><?php esc_html_e('Enable double opt-in', 'mailbluster4wp'); ?></label>
                    <p class="howto"><?php esc_html_e('If checked, the subscriber will receive the opt-in confirmation email', 'mailbluster4wp'); ?></p>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-branding"><?php esc_attr_e('Referral Branding', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="mb4wp-form-branding" class="regular-text" name="mb4wp_form_settings_options[form_branding]" value="yes" <?php checked($form_branding_settings, "yes") ?>>
                    <label for="mb4wp-form-branding"><?php esc_html_e('Show MailBluster branding', 'mailbluster4wp'); ?></label>
                    <p class="howto"><?php esc_html_e('This will be added as a referral branding at the end of the subscription form.', 'mailbluster4wp'); ?></p>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-consent-checkbox"><?php esc_attr_e('Consent', 'mailbluster4wp'); ?></label>
                </th>
                <td class="mb4wp-mt-4">
                    <input type="checkbox" id="mb4wp-form-consent-checkbox" class="regular-text" name="mb4wp_form_settings_options[consent_checkbox]" value="yes" <?php checked($form_consent_settings, "yes") ?>>
                    <label class="" for="mb4wp-form-consent-checkbox"><?php esc_html_e('Include a consent checkbox', 'mailbluster4wp'); ?></label>
                    <div id="mbq_editor" style="display:<?php echo isset($settings['consent_textarea']) ? 'block' : 'none'; ?>">
                        <?php echo (isset($settings['consent_checkbox']) && !empty($settings['consent_textarea'])) ? strip_tags($settings['consent_textarea'], "<p>, <u>, <em>, <strong>, <a>") : MailBluster4WP_Helper::consent_default_textarea(); ?>
                    </div>
                    <input value="<?php echo !empty($settings['consent_textarea']) ? esc_attr($settings['consent_textarea']) : MailBluster4WP_Helper::consent_default_textarea(); ?>" name="mb4wp_form_settings_options[consent_textarea]" id="consent_textarea" type="hidden">
                </td>
            </tr>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-redirectURL"><?php esc_attr_e('Redirect URL', 'mailbluster4wp'); ?></label>
                </th>
                <td class="mb4wp-mt-4">
                    <input type="checkbox" id="mb4wp-form-redirectURL" class="regular-text" name="mb4wp_form_settings_options[redirectURL]" value="yes" <?php checked($form_redirectURL_settings, "yes") ?>>
                    <label class="" for="mb4wp-form-redirectURL"><?php esc_html_e('Instead of thanking the subscriber, this will redirect them to a URL', 'mailbluster4wp'); ?></label>
                    <textarea class="widefat m-2" placeholder="<?php esc_html_e("eg: {$url}/welcome", 'mailbluster4wp'); ?>" id="redirectURL_textarea" name="mb4wp_form_settings_options[redirectURL_textarea]" style="display:<?php echo isset($settings['redirectURL']) ? 'block' : 'none'; ?>"><?php echo (isset($settings['redirectURL_textarea']) && !empty($settings['redirectURL_textarea'])) ? esc_attr($settings['redirectURL_textarea']) : '';?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
</div>