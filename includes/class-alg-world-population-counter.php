<?php
/**
 * World Population Counter - Main Class
 *
 * @version 1.3.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_World_Population_Counter' ) ) :

final class Alg_World_Population_Counter {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WORLD_POPULATION_COUNTER_VERSION;

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
	 *
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
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Admin, e.g., settings
		if ( is_admin() ) {
			$this->admin();
		}

		// Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_and_scripts' ) );

		// AJAX
		add_action( 'wp_ajax_alg_population_counter',        array( $this, 'ajax_alg_population_counter' ) );
		add_action( 'wp_ajax_nopriv_alg_population_counter', array( $this, 'ajax_alg_population_counter' ) );

		// Shortcodes
		add_shortcode( 'alg_world_population_counter', 'alg_world_population_counter' );

		// Widgets
		require_once ( 'class-alg-widget-world-population-counter.php' );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

	}

	/**
	 * localize.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function localize() {
		load_plugin_textdomain( 'world-population-counter', false, dirname( plugin_basename( ALG_WORLD_POPULATION_COUNTER_FILE ) ) . '/langs/' );
	}

	/**
	 * admin.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function admin() {

		// Settings
		require_once ( 'admin/class-alg-settings-world-population-counter.php' );

		// Action link
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WORLD_POPULATION_COUNTER_FILE ), array( $this, 'action_links' ) );

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$settings = '<a href="' . admin_url( 'options-general.php?page=world-population-counter' ) . '">' . __( 'Settings', 'world-population-counter' ) . '</a>';
		return array_merge( array( $settings ), $links );
	}

	/**
	 * enqueue_styles_and_scripts.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    [now] (dev) cleanup & minify 3 x JS files
	 */
	function enqueue_styles_and_scripts() {

		// Number format script
		wp_enqueue_script( 'alg-counter-number-format', $this->plugin_url() . '/includes/js/number-format.js', array( 'jquery' ), $this->version, true );

		// Population now
		$population_now  = $this->get_population_now();

		// Options
		$options = get_option( 'world_population_counter_options', array() );
		if ( ! isset( $options['update_rate_seconds'] ) ) {
			$options['update_rate_seconds'] = 1;
		}
		if ( ! isset( $options['script_type'] ) ) {
			$options['script_type'] = 'ajax';
		}
		$update_rate_ms = $options['update_rate_seconds'] * 1000;

		// Script
		if ( 'ajax' === $options['script_type'] ) {

			// AJAX
			wp_enqueue_script( 'alg-counter-ajax', $this->plugin_url() . '/includes/js/counter-ajax.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'alg-counter-ajax', 'alg_data_counter', array(
				'population_now'  => $population_now['population_now'],
				'update_rate_ms'  => $update_rate_ms,
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
			) );

		} else { // 'simple_js'

			// Simple JS
			$rate = $population_now['rate_per_second'] * $options['update_rate_seconds'];
			wp_enqueue_script( 'alg-counter-simple', $this->plugin_url() . '/includes/js/counter-simple.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'alg-counter-simple', 'alg_data_counter', array(
				'population_now'  => $population_now['population_now'],
				'update_rate_ms'  => $update_rate_ms,
				'rate'            => $rate,
			) );

		}
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
	 * Register Alg_Widget_World_Population_Counter widget.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function register_widget() {
		register_widget( 'Alg_Widget_World_Population_Counter' );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WORLD_POPULATION_COUNTER_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WORLD_POPULATION_COUNTER_FILE ) );
	}


	/**
	 * get_population_now.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_population_now() {

		$starting_time   = 1671224926;
		$starting_point  = 8005781833;
		$rate_per_second = 2.3;

		return array(
			'population_now'  => $starting_point + round( ( gmdate( 'U' ) - $starting_time ) * $rate_per_second ),
			'rate_per_second' => $rate_per_second,
		);

	}

}

endif;
