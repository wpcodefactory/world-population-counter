<?php
/**
 * World Population Counter - Functions
 *
 * @version 1.3.0
 * @since   1.3.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alg_world_population_counter' ) ) {
	/**
	 * alg_world_population_counter.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 *
	 * @todo    (dev) multiple counters on the same page?
	 */
	function alg_world_population_counter() {

		$options = get_option( 'world_population_counter_options', array() );
		if ( ! isset( $options['style'] ) ) {
			$options['style'] = 'font-size: xx-large; font-weight: bold;';
		}
		if ( ! isset( $options['class'] ) ) {
			$options['class'] = 'alg_world_population_counter';
		}

		return '<div id="alg_world_population_counter" class="' . $options['class'] . '" style="' . $options['style'] . '"></div>';

	}
}
