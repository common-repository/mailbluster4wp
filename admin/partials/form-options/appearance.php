<?php
$post_id                    = intval(get_the_ID());
$appearance                 = get_post_meta($post_id, 'mb4wp_form_appearance_options', true);
$custom_class_appearance    = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('custom_class', $post_id);
$theme_appearance           = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('theme', $post_id);
$backgroundColor            = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('background_color', $post_id);
$textColor                  = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('text_color', $post_id);
$textColor                  = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('text_color', $post_id);
$formPaddingY               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('formPaddingY', $post_id);
$formPaddingX               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('formPaddingX', $post_id);
$titleTextSize              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleTextSize', $post_id);
$titleMarginTop             = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginTop', $post_id);
$titleMarginRight           = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginRight', $post_id);
$titleMarginBottom          = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginBottom', $post_id);
$titleMarginLeft            = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('titleMarginLeft', $post_id);
$fieldPaddingY              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('fieldPaddingY', $post_id);
$fieldPaddingX              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('fieldPaddingX', $post_id);
$labelTextSize              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('labelTextSize', $post_id);
$labelTextColor             = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('labelTextColor', $post_id);
$inputPadding               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputPadding', $post_id);
$inputBorderRadius          = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputBorderRadius', $post_id);
$inputBorderWidth           = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputBorderWidth', $post_id);
$inputBorderColor           = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputBorderColor', $post_id);
$inputBackgroundColor       = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputBackgroundColor', $post_id);
$inputTextColor             = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('inputTextColor', $post_id);
$button_color               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('button_color', $post_id);
$btnText_color              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnText_color', $post_id);
$btnText_size               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnText_size', $post_id);
$btnPaddingY                = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnPaddingY', $post_id);
$btnPaddingX                = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnPaddingX', $post_id);
$btnMarginTop               = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnMarginTop', $post_id);
$btnMarginRight             = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnMarginRight', $post_id);
$btnMarginBottom            = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnMarginBottom', $post_id);
$btnMarginLeft              = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnMarginLeft', $post_id);
$btnBorder_width            = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnBorder_width', $post_id);
$btnBorder_color            = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnBorder_color', $post_id);
$btnBorder_radius           = MailBluster4WP_Helper::mb4wp_get_appearance_by_key('btnBorder_radius', $post_id);
?>
<div class="mb4wp-container hidden appearance inside">
    <table class="form-table">
        <tbody>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-custom-class"><?php esc_attr_e('Custom Class', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input type="text" id="mb4wp-form-custom-class" class="regular-text" name="mb4wp_form_appearance_options[custom_class]" value="<?php echo esc_attr($custom_class_appearance); ?>">
                    <p class="howto"><?php esc_html_e('This will be added as a CSS class to the wrapper of the subscription form.', 'mailbluster4wp'); ?></p>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs">
                <th scope="row">
                    <label for="mb4wp-form-theme"><?php esc_attr_e('Theme', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <select id="mb4wp-form-theme" class="regular-text" name="mb4wp_form_appearance_options[theme]">
                        <option value="default" <?php echo $theme_appearance === 'default' ? 'selected' : '' ;?>><?php esc_attr_e('Inherit from theme (default)', 'mailbluster4wp'); ?></option>
                        <option value="light" <?php echo $theme_appearance === 'light' ? 'selected' : '' ;?>><?php esc_attr_e('Light theme', 'mailbluster4wp'); ?></option>
                        <option value="dark" <?php echo $theme_appearance === 'dark' ? 'selected' : '' ;?>><?php esc_attr_e('Dark theme', 'mailbluster4wp'); ?></option>
                        <option value="custom" <?php echo $theme_appearance === 'custom' ? 'selected' : '' ;?>><?php esc_attr_e('Custom theme', 'mailbluster4wp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th style="font-weight: 700;"><?php esc_attr_e('Form', 'mailbluster4wp'); ?></th>
            </tr>
            <!-- form background color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-background-color"><?php esc_attr_e('Background color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $backgroundColor ? $backgroundColor : "#FAFBFC"; ?>" type="color" name="mb4wp_form_appearance_options[background_color]" id="mb4wp-background-color">
                        <label for="mb4wp-background-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- Text color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-text-color"><?php esc_attr_e('Text color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $textColor ? $textColor : "#077BDE"; ?>" type="color" name="mb4wp_form_appearance_options[text_color]" id="mb4wp-text-color">
                        <label for="mb4wp-text-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- Form padding -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-formPaddingY"><?php esc_attr_e('Padding', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <table>
                        <tbody>
                            <!-- top and bottom (Y axis) -->
                            <tr>
                                <th class="label-width" style="padding-top: 0;">
                                    <label for="mb4wp-formPaddingY"><?php esc_attr_e('top and bottom (Y axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input placeholder="for top and bottom" value="<?php echo $formPaddingY ? $formPaddingY : 24; ?>" type="number" min="0" name="mb4wp_form_appearance_options[formPaddingY]" id="mb4wp-formPaddingY">
                                    <label for="mb4wp-formPaddingY"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                            <!-- right and left (X axis) -->
                            <tr>
                                <th style="padding-top: 0; padding-bottom:0;">
                                    <label for="mb4wp-formPaddingX"><?php esc_attr_e('left and right (X axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0; padding-bottom:0;">
                                    <input placeholder="for left and right" value="<?php echo $formPaddingX ? $formPaddingX : 24; ?>" type="number" min="0" name="mb4wp_form_appearance_options[formPaddingX]" id="mb4wp-formPaddingX">
                                    <label for="mb4wp-formPaddingX"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th style="font-weight: 700;"><?php esc_attr_e('Title', 'mailbluster4wp'); ?></th>
            </tr>
            <!-- Title text size -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-title-text-size"><?php esc_attr_e('Text size', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $titleTextSize ? $titleTextSize : 44; ?>" type="number" min="0" name="mb4wp_form_appearance_options[titleTextSize]" id="mb4wp-title-text-size">
                    <label for="mb4wp-title-text-size"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            <!-- Title margin -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-titleMarginTop"><?php esc_attr_e('Margin', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <table>
                        <tbody>
                            <!-- Top -->
                            <tr>
                                <th style="padding: 0;">
                                    <label for="mb4wp-titleMarginTop"><?php esc_attr_e('top:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding: 0;">
                                    <input value="<?php echo $titleMarginTop ? $titleMarginTop : 10; ?>" type="number" name="mb4wp_form_appearance_options[titleMarginTop]" id="mb4wp-titleMarginTop">
                                    <label for="mb4wp-titleMarginTop"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label><br><br>
                                </td>
                            </tr>
                            <!-- Right -->
                            <tr>
                                <th style="padding: 0;">
                                    <label for="mb4wp-titleMarginRight"><?php esc_attr_e('right:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding: 0;">
                                    <input placeholder="for right" value="<?php echo $titleMarginRight ? $titleMarginRight : 0; ?>" type="number" name="mb4wp_form_appearance_options[titleMarginRight]" id="mb4wp-titleMarginRight">
                                    <label for="mb4wp-titleMarginRight"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label><br><br>
                                </td>
                            </tr>
                            <!-- Bottom -->
                            <tr>
                                <th style="padding: 0; width:12%;">
                                    <label for="mb4wp-titleMarginBottom"><?php esc_attr_e('bottom:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding: 0;">
                                    <input placeholder="for bottom" value="<?php echo $titleMarginBottom ? $titleMarginBottom : 20; ?>" type="number" name="mb4wp_form_appearance_options[titleMarginBottom]" id="mb4wp-titleMarginBottom">
                                    <label for="mb4wp-titleMarginBottom"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label><br><br>
                                </td>
                            </tr>
                            <!-- Left -->
                            <tr>
                                <th style="padding: 0;">
                                    <label for="mb4wp-titleMarginLeft"><?php esc_attr_e('left:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding: 0;">
                                    <input placeholder="for left" value="<?php echo $titleMarginLeft ? $titleMarginLeft : 0; ?>" type="number" name="mb4wp_form_appearance_options[titleMarginLeft]" id="mb4wp-titleMarginLeft">
                                    <label for="mb4wp-titleMarginLeft"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <!-- Field padding -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-fieldPaddingY"><?php esc_attr_e('Field padding', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <table>
                        <tbody>
                            <!-- top and bottom (Y axis) -->
                            <tr>
                                <th class="label-width" style="padding-top: 0;">
                                    <label for="mb4wp-fieldPaddingY"><?php esc_attr_e('top and bottom (Y axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input placeholder="for top and bottom" value="<?php echo $fieldPaddingY ? $fieldPaddingY : 5; ?>" type="number" min="0" name="mb4wp_form_appearance_options[fieldPaddingY]" id="mb4wp-fieldPaddingY">
                                    <label for="mb4wp-fieldPaddingY"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                            <!-- right and left (X axis) -->
                            <tr>
                                <th style="padding-top: 0; padding-bottom:0;">
                                    <label for="mb4wp-fieldPaddingX"><?php esc_attr_e('left and right (X axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0; padding-bottom:0;">
                                    <input placeholder="for left and right" value="<?php echo $fieldPaddingX ? $fieldPaddingX : 0; ?>" type="number" min="0" name="mb4wp_form_appearance_options[fieldPaddingX]" id="mb4wp-fieldPaddingX">
                                    <label for="mb4wp-fieldPaddingX"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th style="font-weight: 700;"><?php esc_attr_e('Label', 'mailbluster4wp'); ?></th>
            </tr>
            <!-- Label text size -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-label-text-size"><?php esc_attr_e('Text size', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $labelTextSize ? $labelTextSize : 16; ?>" type="number" min="0" name="mb4wp_form_appearance_options[labelTextSize]" id="mb4wp-label-text-size">
                    <label for="mb4wp-label-text-size"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            <!-- Label text color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-label-text-color"><?php esc_attr_e('Text color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $labelTextColor ? $labelTextColor : "#2B333F" ; ?>" type="color" name="mb4wp_form_appearance_options[labelTextColor]" id="mb4wp-label-text-color">
                        <label for="mb4wp-label-text-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th style="font-weight: 700;"><?php esc_attr_e('Input', 'mailbluster4wp'); ?></th>
            </tr>
            <!-- Input padding -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-inputPadding"><?php esc_attr_e('Padding', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $inputPadding ? $inputPadding : 14; ?>" type="number" min="0" name="mb4wp_form_appearance_options[inputPadding]" id="mb4wp-inputPadding">
                    <label for="mb4wp-inputPadding"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            <!-- Input border radius -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-input-border-radius"><?php esc_attr_e('Border radius', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $inputBorderRadius ? $inputBorderRadius : 3; ?>" type="number" min="0" name="mb4wp_form_appearance_options[inputBorderRadius]" id="mb4wp-input-border-radius">
                    <label for="mb4wp-input-border-radius"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            </tr>
            <!-- Input border width -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-input-border-width"><?php esc_attr_e('Border width', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $inputBorderWidth ? $inputBorderWidth : 1; ?>" type="number" min="0" name="mb4wp_form_appearance_options[inputBorderWidth]" id="mb4wp-input-border-width">
                    <label for="mb4wp-input-border-width"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            <!-- Input border color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-input-border-color"><?php esc_attr_e('Border color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $inputBorderColor ? $inputBorderColor : "#7A879D" ; ?>" type="color" name="mb4wp_form_appearance_options[inputBorderColor]" id="mb4wp-input-border-color">
                        <label for="mb4wp-input-border-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- Input background color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-input-background-color"><?php esc_attr_e('Background color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $inputBackgroundColor ? $inputBackgroundColor : "#FAFAFA"; ?>" type="color" name="mb4wp_form_appearance_options[inputBackgroundColor]" id="mb4wp-input-background-color">
                        <label for="mb4wp-input-background-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- Input text color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-input-text-color"><?php esc_attr_e('Text color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $inputTextColor ? $inputTextColor : "#2B333F" ; ?>" type="color" name="mb4wp_form_appearance_options[inputTextColor]" id="mb4wp-input-text-color">
                        <label for="mb4wp-input-text-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="border-bottom:1px solid #D1D5DD; display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <td style="padding-top: 0;" colspan="100%"></td>
            </tr>
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th style="font-weight: 700;"><?php esc_attr_e('Button', 'mailbluster4wp'); ?></th>
            </tr>
            <!-- Button color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-button-color"><?php esc_attr_e('Color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $button_color ? $button_color : "#077BDE" ; ?>" type="color" name="mb4wp_form_appearance_options[button_color]" id="mb4wp-button-color">
                        <label for="mb4wp-button-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- button text color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnText-color"><?php esc_attr_e('Text color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $btnText_color ? $btnText_color : "#FFFFFF" ; ?>" type="color" name="mb4wp_form_appearance_options[btnText_color]" id="mb4wp-btnText-color">
                        <label for="mb4wp-btnText-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- button text size -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnText-color"><?php esc_attr_e('Text size', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $btnText_size ? $btnText_size : 18; ?>" min="0" type="number" name="mb4wp_form_appearance_options[btnText_size]" id="mb4wp-btnText-size">
                    <label for="mb4wp-btnText-size"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            </tr>
            <!-- Button padding -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnPaddingY"><?php esc_attr_e('Padding', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <table>
                        <tbody>
                            <!-- top and bottom (Y-axis) -->
                            <tr>
                                <th class="label-width" style="padding-top: 0;">
                                    <label for="mb4wp-btnPaddingY"><?php esc_attr_e('top and bottom (Y-axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input placeholder="for top and bottom" value="<?php echo $btnPaddingY ? $btnPaddingY : 12; ?>" type="number" min="0" name="mb4wp_form_appearance_options[btnPaddingY]" id="mb4wp-btnPaddingY">
                                    <label for="mb4wp-btnPaddingY"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                            <!-- right and left (X-axis) -->
                            <tr>
                                <th style="padding-top: 0; padding-bottom:0;">
                                    <label for="mb4wp-btnPaddingX"><?php esc_attr_e('left and right (X-axis)', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0; padding-bottom:0;">
                                    <input placeholder="for left and right" value="<?php echo $btnPaddingX ? $btnPaddingX : 36; ?>" type="number" min="0" name="mb4wp_form_appearance_options[btnPaddingX]" id="mb4wp-btnPaddingX">
                                    <label for="mb4wp-btnPaddingX"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- Button margin -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnMarginTop"><?php esc_attr_e('Margin', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <table>
                        <tbody>
                            <!-- Top -->
                            <tr>
                                <th style="padding-top: 0; width:10%;">
                                    <label for="mb4wp-btnMarginTop"><?php esc_attr_e('top:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input value="<?php echo $btnMarginTop ? $btnMarginTop : 0; ?>" type="number" name="mb4wp_form_appearance_options[btnMarginTop]" id="mb4wp-btnMarginTop">
                                    <label for="mb4wp-btnMarginTop"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>&nbsp;
                                </td>
                            </tr>
                            <!-- Right -->
                            <tr>
                                <th style="padding-top: 0;">
                                    <label for="mb4wp-btnMarginRight"><?php esc_attr_e('right:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input placeholder="for right" value="<?php echo $btnMarginRight ? $btnMarginRight : 0; ?>" type="number" name="mb4wp_form_appearance_options[btnMarginRight]" id="mb4wp-btnMarginRight">
                                    <label for="mb4wp-btnMarginRight"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>&nbsp;
                                </td>
                            </tr>
                            <!-- Bottom -->
                            <tr>
                                <th style="padding-top: 0;">
                                    <label for="mb4wp-btnMarginBottom"><?php esc_attr_e('bottom:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0;">
                                    <input placeholder="for bottom" value="<?php echo $btnMarginBottom ? $btnMarginBottom : 16; ?>" type="number" name="mb4wp_form_appearance_options[btnMarginBottom]" id="mb4wp-btnMarginBottom">
                                    <label for="mb4wp-btnMarginBottom"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>&nbsp;
                                </td>
                            </tr>
                            <!-- Left -->
                            <tr>
                                <th style="padding-top: 0; padding-bottom:0;">
                                    <label for="mb4wp-btnMarginLeft"><?php esc_attr_e('left:', 'mailbluster4wp'); ?></label>
                                </th>
                                <td style="padding-top: 0; padding-bottom:0;">
                                    <input placeholder="for left" value="<?php echo $btnMarginLeft ? $btnMarginLeft : 0; ?>" type="number" name="mb4wp_form_appearance_options[btnMarginLeft]" id="mb4wp-btnMarginLeft">
                                    <label for="mb4wp-btnMarginLeft"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- border width -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnBorder_width"><?php esc_attr_e('Border width', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $btnBorder_width ? $btnBorder_width : 0; ?>" type="number" min="0" name="mb4wp_form_appearance_options[btnBorder_width]" id="mb4wp-btnBorder_width">
                    <label for="mb4wp-btnBorder_width"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
            <!-- Button border color -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnBorder-color"><?php esc_attr_e('Border color', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <span>
                        <input value="<?php echo $btnBorder_color ? $btnBorder_color : "#FFFFFF" ; ?>" type="color" name="mb4wp_form_appearance_options[btnBorder_color]" id="mb4wp-btnBorder-color">
                        <label for="mb4wp-btnBorder-color"><?php esc_attr_e('Select color', 'mailbluster4wp'); ?></label>
                    </span>
                </td>
            </tr>
            <!-- Button border radius -->
            <tr class="mb4wp-text-inputs custom-theme" style="display:<?php echo $theme_appearance === 'custom' ? 'table-row' : 'none'; ?>">
                <th scope="row">
                    <label for="mb4wp-btnBorder_radius"><?php esc_attr_e('Border radius', 'mailbluster4wp'); ?></label>
                </th>
                <td>
                    <input value="<?php echo $btnBorder_radius ? $btnBorder_radius : 3; ?>" min="0" type="number" name="mb4wp_form_appearance_options[btnBorder_radius]" id="mb4wp-btnBorder_radius">
                    <label for="mb4wp-btnBorder_radius"><?php esc_attr_e('px', 'mailbluster4wp'); ?></label>
                </td>
            </tr>
        </tbody>
    </table>
</div>