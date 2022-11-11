<?php
/*
Plugin Name: World Population Counter
Plugin URI: https://algoritmika.com
Description: World population counter.
Version: 1.2.0
Author: Algoritmika Ltd
Author URI: https://algoritmika.com
Text Domain: world-population-counter
Domain Path: /langs
Copyright: © 2020 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_World_Population_Counter' ) ) :

/**
 * Main Alg_World_Population_Counter Class
 *
 * @class   Alg_World_Population_Counter
 * @version 1.2.0
 * @since   1.0.0
 */
final class Alg_World_Population_Counter {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.2.0';

	/**
	 * @var   Alg_World_Population_Counter The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_World_Population_Counter Instance
	 *
	 * Ensures only one instance of Alg_World_Population_Counter is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_World_Population_Counter - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_World_Population_Counter Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {
		// Set up localisation
		load_plugin_textdomain( 'world-population-counter', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
		// Settings
		if ( is_admin() ) {
			require_once ( 'includes/admin/class-alg-settings-world-population-counter.php' );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
		// Actions
		add_action( 'wp_enqueue_scripts',                    array( $this, 'enqueue_styles_and_scripts' ) );
		add_action( 'wp_ajax_alg_population_counter',        array( $this, 'ajax_alg_population_counter' ) );
		add_action( 'wp_ajax_nopriv_alg_population_counter', array( $this, 'ajax_alg_population_counter' ) );
		// Shortcodes
		add_shortcode( 'alg_world_population_counter', 'alg_world_population_counter' );
		// Widgets
		require_once ( 'includes/class-alg-widget-world-population-counter.php' );
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array(
			'<a href="' . admin_url( 'options-general.php?page=world-population-counter-settings-admin' ) . '">' . __( 'Settings', 'world-population-counter' ) . '</a>',
		);
		return array_merge( $custom_links, $links );
	}

	/**
	 * enqueue_styles_and_scripts.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function enqueue_styles_and_scripts() {

		wp_enqueue_script( 'alg-counter-number-format-js', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/includes/js/number-format.js', array( 'jquery' ), $this->version, true );

		$population_now  = $this->get_population_now();

		$options = get_option( 'world_population_counter_options', array() );
		if ( ! isset( $options['update_rate_seconds'] ) ) {
			$options['update_rate_seconds'] = 1;
		}
		if ( ! isset( $options['script_type'] ) ) {
			$options['script_type'] = 'ajax';
		}
		$update_rate_ms  = $options['update_rate_seconds'] * 1000;
		if ( 'ajax' === $options['script_type'] ) {
			wp_enqueue_script( 'alg-counter-js', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/includes/js/counter-ajax.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'alg-counter-js', 'alg_data_counter', array(
				'population_now'  => $population_now['population_now'],
				'update_rate_ms'  => $update_rate_ms,
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
			) );
		} else {
			$rate = $population_now['rate_per_second'] * $options['update_rate_seconds'];
			wp_enqueue_script( 'alg-counter-js', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/includes/js/counter-simple.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'alg-counter-js', 'alg_data_counter', array(
				'population_now'  => $population_now['population_now'],
				'update_rate_ms'  => $update_rate_ms,
				'rate'            => $rate,
			) );
		}
	}

	/**
	 * get_population_now.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_population_now() {
		$seconds_passed     = ( gmdate( 'U' ) - 1479233101 );
		$starting_point     = 7464554876;
		$rate_per_second    = ( 1.13 / 31557600 / 100 ) * $starting_point;
		$population_now     = $starting_point + round( $seconds_passed * $rate_per_second );
		return array(
			'population_now'  => $population_now,
			'rate_per_second' => $rate_per_second,
		);
	}

	/**
	 * ajax_alg_population_counter.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function ajax_alg_population_counter() {
		$result = $this->get_population_now();
		echo $result['population_now'];
		die();
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_world_population_counter' ) ) {
	/**
	 * alg_world_population_counter.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
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

if ( ! function_exists( 'alg_world_population_counter_instance' ) ) {
	/**
	 * Returns the main instance of Alg_World_Population_Counter to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_World_Population_Counter
	 */
	function alg_world_population_counter_instance() {
		return Alg_World_Population_Counter::instance();
	}
}

alg_world_population_counter_instance();
