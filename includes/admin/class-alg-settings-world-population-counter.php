<?php
/**
 * World Population Counter - Settings
 *
 * @version 1.4.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Settings_World_Population_Counter' ) ) :

class Alg_Settings_World_Population_Counter {

	/**
	 * Holds the values to be used in the fields callbacks.
	 *
	 * @since   1.0.0
	 */
	private $options;

	/**
	 * Start up.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @see     https://codex.wordpress.org/Creating_Options_Pages
	 *
	 * @todo    (dev) check sanitization, etc.
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_plugin_page() {
		add_options_page(
			__( 'World Population Counter Settings', 'world-population-counter' ),
			__( 'World Population Counter', 'world-population-counter' ),
			'manage_options',
			'world-population-counter',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function create_admin_page() {
		$this->options = get_option( 'world_population_counter_options', array() );
		?><div class="wrap">
			<h1><?php echo __( 'World Population Counter', 'world-population-counter' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'world_population_counter_option_group' );
				do_settings_sections( 'world-population-counter' );
				submit_button();
			?>
			</form>
		</div><?php
	}

	/**
	 * Register and add settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function page_init() {

		register_setting(
			'world_population_counter_option_group',
			'world_population_counter_options',
			array( $this, 'sanitize' )
		);

		// "Settings" section
		add_settings_section(
			'world_population_counter_setting_section_id',
			__( 'Settings', 'world-population-counter' ),
			array( $this, 'print_section_info' ),
			'world-population-counter'
		);

		// "Settings" section fields
		add_settings_field(
			'update_rate_seconds',
			__( 'Update rate (seconds)', 'world-population-counter' ),
			array( $this, 'update_rate_seconds_callback' ),
			'world-population-counter',
			'world_population_counter_setting_section_id'
		);
		add_settings_field(
			'script_type',
			__( 'Script type', 'world-population-counter' ),
			array( $this, 'script_type_callback' ),
			'world-population-counter',
			'world_population_counter_setting_section_id'
		);
		add_settings_field(
			'style',
			__( 'CSS style', 'world-population-counter' ),
			array( $this, 'style_callback' ),
			'world-population-counter',
			'world_population_counter_setting_section_id'
		);
		add_settings_field(
			'class',
			__( 'CSS class', 'world-population-counter' ),
			array( $this, 'class_callback' ),
			'world-population-counter',
			'world_population_counter_setting_section_id'
		);

		// "Data" section
		add_settings_section(
			'world_population_counter_data_settings_section_id',
			__( 'Data', 'world-population-counter' ),
			array( $this, 'print_data_section_info' ),
			'world-population-counter'
		);

		// "Data" section fields
		add_settings_field(
			'starting_time',
			__( 'Starting time', 'world-population-counter' ),
			array( $this, 'starting_time_callback' ),
			'world-population-counter',
			'world_population_counter_data_settings_section_id'
		);
		add_settings_field(
			'starting_point',
			__( 'Starting population', 'world-population-counter' ),
			array( $this, 'starting_point_callback' ),
			'world-population-counter',
			'world_population_counter_data_settings_section_id'
		);
		add_settings_field(
			'rate_per_second',
			__( 'Population growth (per second)', 'world-population-counter' ),
			array( $this, 'rate_per_second_callback' ),
			'world-population-counter',
			'world_population_counter_data_settings_section_id'
		);

	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @param   array $input Contains all settings fields as array keys
	 *
	 * @todo    (dev) better sanitization functions, e.g., `starting_point`, `rate_per_second`
	 */
	function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['update_rate_seconds'] ) ) {
			$new_input['update_rate_seconds'] = absint( $input['update_rate_seconds'] );
		}

		if ( isset( $input['script_type'] ) ) {
			$new_input['script_type']         = sanitize_text_field( $input['script_type'] );
		}

		if ( isset( $input['style'] ) ) {
			$new_input['style']               = sanitize_text_field( $input['style'] );
		}

		if ( isset( $input['class'] ) ) {
			$new_input['class']               = sanitize_text_field( $input['class'] );
		}

		if ( isset( $input['starting_time'] ) ) {
			$new_input['starting_time']       = sanitize_text_field( $input['starting_time'] );
		}

		if ( isset( $input['starting_point'] ) ) {
			$new_input['starting_point']      = sanitize_text_field( $input['starting_point'] );
		}

		if ( isset( $input['rate_per_second'] ) ) {
			$new_input['rate_per_second']     = sanitize_text_field( $input['rate_per_second'] );
		}

		return $new_input;
	}

	/**
	 * print_data_section_info.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function print_data_section_info() {
		echo '<p>' . esc_html__( 'Leave empty to use the default values.', 'world-population-counter' ) . '</p>';
	}

	/**
	 * starting_time_callback.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function starting_time_callback() {
		printf(
			'<input type="date" id="starting_time" name="world_population_counter_options[starting_time]" value="%s" style="width:50%%; min-width:300px;" />' .
			'<p class="description" id="starting_time-description">' . 'E.g.: <code>2023-11-24</code>' . '</p>',
				isset( $this->options['starting_time'] ) ? esc_attr( $this->options['starting_time'] ) : ''
		);
	}

	/**
	 * starting_point_callback.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function starting_point_callback() {
		printf(
			'<input type="number" id="starting_point" name="world_population_counter_options[starting_point]" value="%s" style="width:50%%; min-width:300px;" />' .
			'<p class="description" id="starting_point-description">' . 'E.g.: <code>8074720000</code>' . '</p>',
				isset( $this->options['starting_point'] ) ? esc_attr( $this->options['starting_point'] ) : ''
		);
	}

	/**
	 * rate_per_second_callback.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function rate_per_second_callback() {
		printf(
			'<input type="number" step="0.001" id="rate_per_second" name="world_population_counter_options[rate_per_second]" value="%s" style="width:50%%; min-width:300px;" />' .
			'<p class="description" id="rate_per_second-description">' . 'E.g.: <code>2.329</code>' . '</p>',
				isset( $this->options['rate_per_second'] ) ? esc_attr( $this->options['rate_per_second'] ) : ''
		);
	}

	/**
	 * Print the Section text.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function print_section_info() {
		echo '<p>' .
			sprintf( __( 'Counter can be added via: %s widget, %s shortcode or %s PHP function.', 'world-population-counter' ),
				'<em>' . __( 'World Population Counter', 'world-population-counter' ) . '</em>',
				'<code>[alg_world_population_counter]</code>',
				'<code>echo alg_world_population_counter();</code>'
			) .
		'</p>';
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function update_rate_seconds_callback() {
		printf(
			'<input type="number" id="update_rate_seconds" name="world_population_counter_options[update_rate_seconds]" value="%s" min="1" />',
				isset( $this->options['update_rate_seconds'] ) ? esc_attr( $this->options['update_rate_seconds']) : 1
		);
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function script_type_callback() {
		$selected = isset( $this->options['script_type'] ) ? esc_attr( $this->options['script_type']) : 'ajax';
		printf(
			'<select id="script_type" name="world_population_counter_options[script_type]" />' .
				'<option value="ajax" '      . selected( $selected, 'ajax',      false ) . '>' . __( 'AJAX', 'world-population-counter' )      . '</option>' .
				'<option value="simple_js" ' . selected( $selected, 'simple_js', false ) . '>' . __( 'Simple JS', 'world-population-counter' ) . '</option>' .
			'</select>' .
			'<p class="description" id="script-type-description">' . __( 'AJAX is more accurate. Simple JS requires less resources.', 'world-population-counter' ) . '</p>'
		);
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function style_callback() {
		printf(
			'<input type="text" id="style" name="world_population_counter_options[style]" value="%s" style="width:50%%; min-width:300px;" />' .
			'<p class="description" id="style-description">' . 'E.g.: <code>font-size: xx-large; font-weight: bold;</code>' . '</p>',
				isset( $this->options['style'] ) ? esc_attr( $this->options['style'] ) : 'font-size: xx-large; font-weight: bold;'
		);
	}

	/**
	 * Get the settings option array and print one of its values.
	 *
	 * @version 1.1.1
	 * @since   1.1.1
	 */
	function class_callback() {
		printf(
			'<input type="text" id="class" name="world_population_counter_options[class]" value="%s" style="width:50%%; min-width:300px;" />',
				isset( $this->options['class'] ) ? esc_attr( $this->options['class'] ) : 'alg_world_population_counter'
		);
	}

}

endif;

return new Alg_Settings_World_Population_Counter();
