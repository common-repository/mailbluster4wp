<?php
if (!defined('ABSPATH')) {
	die('No direct access.');
}

/**
 * The mailbluster form option widget class.
 *
 * This is extending wordpress default widget class.
 *
 *
 *
 * @since      1.0.0
 * @package    MailBluster4WP
 * @subpackage MailBluster4WP/admin
 * @author     MailBluster <hello@mailbluster.com>
 */
class MailBluster4WP_Widget extends WP_Widget {

	/**
	 * Construct widget
     *
     * @since 1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'mb4wp_widget',
			'description' => esc_html__( 'Displays a MailBluster subscription forms.', 'mailbluster4wp' ),
		);
		parent::__construct( 'mb4wp_widget', esc_html__('MailBluster Form', 'mailbluster4wp'), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args
	 * @param array $instance
     *
     * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args );

		$form_id = intval(!empty($instance['id']) ? $instance['id'] : 0 );
		$form_description = get_post_meta(intval($form_id), 'mb4wp_form_description', true);

		$form_title = esc_html( get_the_title($form_id) );
		$widget_output = $before_widget;
		$widget_output .= $before_title;
		$widget_output .= $form_title;
		$widget_output .= $after_title;
		if(!empty($form_description)) {
			$widget_output .= '<p>'.esc_html($form_description).'</p>';
		}
		$widget_output .= MailBluster4WP_Helper::mb4wp_render_form($form_id);
		$widget_output .= $after_widget;

		echo $widget_output;

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
     *
     * @since 1.0.0
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'mb4wpform',
			'post_status'  => 'publish'
		);
		$forms_post_object = get_posts( $args );

		$status     = MailBluster4WP_Helper::mb4wp_get_response_status();
		$message    = MailBluster4WP_Helper::mb4wp_get_response_message();

		if(!$status || !is_array($message)) {
			?>
			<p>
				<?php
                sprintf( wp_kses( __( '<strong>%s.</strong> Please check <a href="%s">api settings</a>.', 'mailbluster4wp' ), array(  'a' => array( 'href' => array() ), 'strong' => array() ) ),
                    (is_array($message))? esc_html__('API key valid but inactive', 'mailbluster4wp') : ucwords($message),
                    esc_url(admin_url('edit.php?post_type=mb4wpform&page=mb4wpform-api'))
                );
                ?>
			</p>
			<?php
			return;
		}

		if (empty($forms_post_object)) {
			echo sprintf('<p>%s <a href="%s">%s</a></p>',
                esc_html__('There are no forms to select.', 'mailbluster4wp'),
				esc_url(admin_url('post-new.php?post_type=mb4wpform')),
                esc_html__('Create one from here','mailbluster4wp')
                );
			return;
		}
		$form_id = intval(!empty($instance['id']) ? $instance['id'] : 0);
		?>
		<p>
			<select class="mb4wp-form-select widefat" name="<?php echo esc_attr($this->get_field_name('mb4wp-form-select')); ?>">
				<option value="0"><?php esc_html_e('Select a form', 'mailbluster4wp'); ?></option>
				<?php
				foreach ($forms_post_object as $form_data) {
					printf(
						'<option value="%s" %s">%s</option>',
						absint($form_data->ID),
						selected($form_id, $form_data->ID, false),
						wp_kses_post($form_data->post_title)
					);
				} ?>
			</select>
		</p>
		<p>
			<?php
			echo sprintf(
				wp_kses( __( 'Further customization go to <a href="%s">All Forms</a> settings screen.', 'mailbluster4wp' ), array(  'a' => array( 'href' => array() ) ) ),
				esc_url( admin_url('edit.php?post_type=mb4wpform') )
			);
			?>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
     * @since 1.0.0
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		return array(
			'id' => sanitize_text_field($new_instance['mb4wp-form-select']),
		);
	}
}