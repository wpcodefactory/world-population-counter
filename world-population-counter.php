<?php
/*
Plugin Name: World Population Counter
Plugin URI: https://wpfactory.com
Description: Adds live world population counter to your site.
Version: 1.4.0
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: world-population-counter
Domain Path: /langs
*/

defined( 'ABSPATH' ) || exit;

defined( 'ALG_WORLD_POPULATION_COUNTER_VERSION' ) || define( 'ALG_WORLD_POPULATION_COUNTER_VERSION', '1.4.0' );

defined( 'ALG_WORLD_POPULATION_COUNTER_FILE' ) || define( 'ALG_WORLD_POPULATION_COUNTER_FILE', __FILE__ );

require_once( 'includes/alg-world-population-counter-functions.php' );

require_once( 'includes/class-alg-world-population-counter.php' );

if ( ! function_exists( 'alg_world_population_counter_instance' ) ) {
	/**
	 * Returns the main instance of Alg_World_Population_Counter to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_world_population_counter_instance() {
		return Alg_World_Population_Counter::instance();
	}
}

add_action( 'plugins_loaded', 'alg_world_population_counter_instance' );
