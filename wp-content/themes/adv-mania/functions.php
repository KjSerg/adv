<?php
add_theme_support( 'custom-logo' );

add_theme_support( 'menus' );
function advMania_register_menus() {
	register_nav_menus(
		array(
			'header-menu' => __( 'Header Menu' )
		)
	);
}

add_action( 'init', 'advMania_register_menus' );

function advMania_enqueue_styles() {
	wp_enqueue_style( 'advMania-style', get_stylesheet_uri() );

	wp_enqueue_style( 'advMania-fonts-style', get_template_directory_uri() . '/assets/css/fonts.css' );

	wp_enqueue_style( 'advMania-main-style', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_style( 'advMania-fix-style', get_template_directory_uri() . '/assets/css/fix.css' );
	wp_enqueue_style( 'advMania-fix-css', get_template_directory_uri() . '/assets/css/css.css' );


	wp_enqueue_script( 'advMania-libs-scripts', get_template_directory_uri() . '/assets/js/libs.min.js', array(), '1.1', true );

	wp_enqueue_script( 'advMania-scripts', get_template_directory_uri() . '/assets/js/common.js', array(), '1.1', true );

	wp_enqueue_script( 'advMania-scripts-booking', get_template_directory_uri() . '/assets/js/booking.js', array(), '1.1', true );

	wp_enqueue_script( 'advMania-scripts-fix', get_template_directory_uri() . '/assets/js/fix.js', array(), '1.1', true );

	wp_localize_script( 'ajax-script', 'AJAX', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}

add_action( 'wp_enqueue_scripts', 'advMania_enqueue_styles' );


get_template_part( 'functions/ajax-functions' );
require_once get_template_directory() . '/functions/helper.php';
get_template_part( 'functions/settings' );
get_template_part( 'functions/carbon-settings' );
get_template_part( 'functions/functions-booking' );
get_template_part( 'functions/checkout-order' );
get_template_part( 'functions/Ajax' );
get_template_part( 'functions/components' );


add_action( 'init', function () {
	pll_register_string( 'Book_a_tour', 'Book a tour' );
	pll_register_string( 'Book', 'Book' );
	pll_register_string( 'Booking', 'Booking' );
	pll_register_string( 'Main', 'Main' );
	pll_register_string( 'Tours', 'Tours' );
	pll_register_string( 'Motorcycle', 'Motorcycle' );
	pll_register_string( 'Booking tours', 'Booking tours' );
	pll_register_string( 'Booking Motorcycle', 'Booking Motorcycle' );
	pll_register_string( 'copyright', 'copyright' );
	pll_register_string( 'Choise Motorcycle', 'Choise Motorcycle' );
	pll_register_string( 'Choise Tour', 'Choise Tour' );
	pll_register_string( 'Booking information', 'Booking information' );
	pll_register_string( 'Success', 'Success' );
	pll_register_string( 'tours', 'tours' );
	pll_register_string( 'motorcycle', 'motorcycle' );
	pll_register_string( 'Search', 'Search' );
	pll_register_string( 'Read More', 'Read More' );
	pll_register_string( 'Sort by Name', 'Sort by Name' );
	pll_register_string( 'Sort by Date', 'Sort by Date' );
	pll_register_string( 'Sort by Months', 'Sort by Months' );
	pll_register_string( 'Next Step', 'Next Step' );
	pll_register_string( 'Find out more', 'Find out more' );
	pll_register_string( 'See all tours', 'See all tours' );
	pll_register_string( 'Choose', 'Choose' );
	pll_register_string( 'Leave a Request', 'Leave a Request' );
	pll_register_string( 'Got a question', 'Got a question' );
	pll_register_string( 'First Name and Last Name', 'First Name and Last Name' );
	pll_register_string( 'Name', 'Name' );
	pll_register_string( 'Phone', 'Phone' );
	pll_register_string( 'phone placeholder', 'phone placeholder' );
	pll_register_string( 'Email', 'Email' );
	pll_register_string( 'email placeholder', 'email placeholder' );
	pll_register_string( 'Send', 'Send' );
	pll_register_string( 'Success Message', 'Success Message' );
	pll_register_string( 'Thank you', 'Thank you' );
	pll_register_string( 'Tour dates', 'Tour dates' );
	pll_register_string( 'thank_you', 'thank_you' );
	pll_register_string( 'info_calendar_bike', 'info calendar bike' );
	pll_register_string( 'info_calendar_tour', 'info calendar tour' );
	pll_register_string( 'info_calendar_single_tour', 'info calendar single tour' );
	pll_register_string( 'Rent_on_day', 'Rent on day' );
	pll_register_string( 'In_stock', 'In stock' );
	pll_register_string( 'from', 'From' );
	pll_register_string( 'view', 'View' );
	pll_register_string( 'tours_and_trips', 'tours and trips' );
	pll_register_string( 'new_countries', 'new countries' );
	pll_register_string( 'Add_to_booking', 'Add to booking' );
	pll_register_string( 'Rent_for', 'Rent for' );
	pll_register_string( 'Days', 'Days' );
	pll_register_string( 'Cancel', 'Cancel' );
	pll_register_string( 'eserve', 'Reserve' );
	pll_register_string( 'Total_price', 'Total price' );
	pll_register_string( 'Total_price_bike', 'Total price bike' );
	pll_register_string( 'Choose_Your_Next', 'Choose Your Next' );
	pll_register_string( 'Basу_rent_a_day', 'Basу rent a day' );
	pll_register_string( 'All_tours_are_reserved', 'All tours are reserved' );
	pll_register_string( 'All_tours_are_reserved', 'All tours' );
	pll_register_string( 'Selected_dates', 'Selected dates' );
	pll_register_string( 'Change_the_date', 'Change the date' );
	pll_register_string( 'Additional_equipment', 'Additional equipment' );
	pll_register_string( 'Choose_Motorcycle', 'Choose Motorcycle' );
	pll_register_string( 'Add_to_rent', 'Add to rent' );
	pll_register_string( 'No_tours_available', 'No tours available' );
	pll_register_string( 'Card_name', 'Card name' );
	pll_register_string( 'Card_number', 'Card number' );
	pll_register_string( 'MM/YY', 'MM/YY' );
	pll_register_string( 'CVV', 'CVV' );
	pll_register_string( 'Base_on', 'Base on' );
	pll_register_string( 'Вы соглашаетесь с', 'Вы соглашаетесь с' );
	pll_register_string( 'apply', 'apply' );
	pll_register_string( 'Country', 'Country' );
	pll_register_string( 'Country placeholder', 'Country placeholder' );
	pll_register_string( 'Получить скидку', 'Получить скидку' );

} );