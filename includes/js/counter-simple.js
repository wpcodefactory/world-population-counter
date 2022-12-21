/**
 * World Population Counter - Simple
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

var amount = document.getElementById( 'alg_world_population_counter' );
if ( amount !== null ) {
	var current   = parseInt( alg_data_counter.population_now );
	var rate      = parseFloat( alg_data_counter.rate );
	var update_ms = parseInt( alg_data_counter.update_rate_ms );
	update();

	function update() {
		amount.innerText = alg_number_format( Math.round( current ), 0, '', ',' );
	}

	setInterval( function () {
		current += rate;
		update();
	}, update_ms );
}
