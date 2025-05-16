<?php
function generate_country_select($value = ''): string {
	// Визначаємо поточну мову з Polylang
	$current_language = function_exists( 'pll_current_language' ) ? pll_current_language( 'locale' ) : 'en_US';

	// Отримуємо мовний код із локалі (наприклад, 'uk' із 'uk_UA')
	$language_code = explode( '_', $current_language )[0];

	// Rest Countries API використовує трибуквені коди (ISO 639-3), але ми спробуємо двобуквений код
	// Якщо код не підтримується, повертаємося до 'eng'
	$api_language = in_array( $language_code, [ 'uk', 'en', 'fr', 'de', 'es', 'pl', 'it' ] ) ? $language_code : 'eng';

	// Спробуємо отримати кеш
	$cache_key = 'country_list_' . $api_language;
	$countries = get_transient( $cache_key );

	if ( false === $countries ) {
		$response = wp_remote_get( 'https://restcountries.com/v3.1/all' );
		if ( ! is_wp_error( $response ) ) {
			$countries = json_decode( wp_remote_retrieve_body( $response ), true );
			// Сортуємо країни за назвою для поточної мови
			usort( $countries, function ( $a, $b ) use ( $api_language ) {
				$name_a = $a['translations'][ $api_language ]['common'] ?? $a['name']['common'];
				$name_b = $b['translations'][ $api_language ]['common'] ?? $b['name']['common'];

				return strcmp( $name_a, $name_b );
			} );
			set_transient( $cache_key, $countries, ( DAY_IN_SECONDS * 7 ) ); // Кеш
		} else {
			$countries = []; // Резервний порожній масив
		}
	}
	if(!$value){

	}

	// Генеруємо HTML селект
	$output = '<select name="country" id="country" required class="country-select select">';
	$output .= '<option value="">' . pll__( 'Country placeholder' ) . '</option>';

	foreach ( $countries as $country ) {
		$code   = $country['cca2'];
		$name   = $country['translations'][ $api_language ]['common'] ?? $country['name']['common'];
		$attr = $value == $code ? ' selected="selected"' : '';
		$output .= '<option ' . esc_attr( $attr ) . ' value="' . esc_attr( $code ) . '">' . esc_html( $name ) . '</option>';
	}

	$output .= '</select>';

	return $output;
}
