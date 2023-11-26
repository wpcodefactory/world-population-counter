<?php
/**
 * World Population Counter - Widget
 *
 * @version 1.3.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Widget_World_Population_Counter' ) ) :

class Alg_Widget_World_Population_Counter extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) check sanitization, etc.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'alg_widget_world_population_counter',
			'description' => __( 'World Population Counter Widget', 'world-population-counter' ),
		);
		parent::__construct( 'alg_widget_world_population_counter', __( 'World Population Counter', 'world-population-counter' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   array $args
	 * @param   array $instance
	 */
	function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo alg_world_population_counter();
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   array $instance The widget options
	 */
	function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?><p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'world-population-counter' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p><?php
	}

	/**
	 * Processing widget options on save.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param   array $new_instance The new options
	 * @param   array $old_instance The previous options
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}

endif;
