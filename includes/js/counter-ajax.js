/**
 * World Population Counter - AJAX
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

var amount = document.getElementById( 'alg_world_population_counter' );
if ( amount !== null ) {
	var update_ms    = parseInt( alg_data_counter.update_rate_ms );
	amount.innerText = alg_number_format( parseInt( alg_data_counter.population_now ), 0, '', ',' );
	update();

	function update() {
		var data = {
			'action': 'alg_population_counter',
		};
		jQuery.post( alg_data_counter.ajax_url, data, function ( response ) {
			amount.innerText = alg_number_format( response, 0, '', ',' );
		} );
	}

	setInterval( function () {
		update();
	}, update_ms );
}
