<?php

/**
 * Provide a API page view in admin area for the plugin
 *
 * This file is used to markup the API page of the plugin.
 *
 * @since      1.0.0
 *
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 class="wp-heading"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="card">
        <form method="post" action="options.php" id="mb4wp-options">

            <?php
            settings_fields('mb4wp_api');
            do_settings_sections('mb4wp_api');
            submit_button();
            ?>
        </form>
    </div>

</div>