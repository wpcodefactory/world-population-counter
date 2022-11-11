<?php
/**
 * World Population Counter - Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 * @see     https://codex.wordpress.org/Creating_Options_Pages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alg_Settings_World_Population_Counter' ) ) :

class Alg_Settings_World_Population_Counter {

	/**
	 * Holds the values to be used in the fields callbacks
	 *
	 * @since   1.0.0
	 */
	private $options;

	/**
	 * Start up.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			__( 'World Population Counter Settings', 'world-population-counter' ),
			__( 'World Population Counter', 'world-population-counter' ),
			'manage_options',
			'world-population-counter-settings-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function create_admin_page() {
		// Set class property
		$this->options = get_option( 'world_population_counter_options' );
		?><div class="wrap">
			<h1><?php echo __( 'World Population Counter', 'world-population-counter' ); ?></h1>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'world_population_counter_option_group' );
				do_settings_sections( 'world-population-counter-settings-admin' );
				submit_button();
			?>
			</form>
		</div><?php
	}

	/**
	 * Register and add settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function page_init() {
		register_setting(
			'world_population_counter_option_group',                   // Option group
			'world_population_counter_options',                        // Option name
			array( $this, 'sanitize' )                                 // Sanitize
		);
		add_settings_section(
			'world_population_counter_setting_section_id',             // ID
			__( 'Settings', 'world-population-counter' ),              // Title
			array( $this, 'print_section_info' ),                      // Callback
			'world-population-counter-settings-admin'                  // Page
		);
		add_settings_field(
			'update_rate_seconds', // ID
			__( 'Update rate (seconds)', 'world-population-counter' ), // Title
			array( $this, 'update_rate_seconds_callback' ),            // Callback
			'world-population-counter-settings-admin',                 // Page
			'world_population_counter_setting_section_id'              // Section
		);
		add_settings_field(
			'script_type',
			__( 'Script type', 'world-population-counter' ),
			array( $this, 'script_type_callback' ),
			'world-population-counter-settings-admin',
			'world_population_counter_setting_section_id'
		);
		add_settings_field(
			'style',
			__( 'CSS style', 'world-population-counter' ),
			array( $this, 'style_callback' ),
			'world-population-counter-settings-admin',
			'world_population_counter_setting_section_id'
		);
		add_settings_field(
			'class',
			__( 'CSS class', 'world-population-counter' ),
			array( $this, 'class_callback' ),
			'world-population-counter-settings-admin',
			'world_population_counter_setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 * @param   array $input Contains all settings fields as array keys
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

		return $new_input;
	}

	/**
	 * Print the Section text.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 */
	function print_section_info() {
		$html = '';
		$html .= '<p>';
		$html .= sprintf(
			__( 'Counter can be added via: %s widget, %s shortcode or %s PHP function.', 'world-population-counter' ),
			'<em>' . __( 'World Population Counter', 'world-population-counter' ) . '</em>',
			'<code>[alg_world_population_counter]</code>',
			'<code>echo alg_world_population_counter();</code>' );
		$html .= '<p>';
		echo $html;
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
			'<option value="ajax" ' . selected( $selected, 'ajax', false ) . '>' . __( 'AJAX', 'world-population-counter' ) . '</option>' .
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
