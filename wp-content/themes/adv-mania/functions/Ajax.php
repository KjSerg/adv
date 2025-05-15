<?php

namespace ADV\Core;


class Ajax {
	private static ?self $instance = null;

	private function __construct() {
		$this->initialize();
	}

	public static function get_instance(): self {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function initialize(): void {
		add_action( 'wp_ajax_nopriv_set_promo_code', [ $this, 'set_promo_code' ] );
		add_action( 'wp_ajax_set_promo_code', [ $this, 'set_promo_code' ] );

	}

	public function set_promo_code(): void {
		$code = filter_input( INPUT_POST, 'val' );
		if ( ! $code ) {
			$this->send_error( 'Empty Promo Code' );
		}
		$coupon = self::get_promo_code( $code );
		if ( $coupon['id'] == 0 || $coupon['percent'] == 0 ) {
			$this->send_error( 'Invalid Promo Code' );
		}
		$this->send_response( array_merge( $coupon, [
			'msg' => 'Promo Code Set Successfully'
		] ) );
	}

	public static function get_promo_code( $coupon ): array {
		$res = array(
			'id'      => 0,
			'percent' => 0,
			'title'   => $coupon,
		);
		global $wpdb;
		$post = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'promocode' AND post_status = 'publish' AND BINARY post_title = %s LIMIT 1", $coupon )
		);
		if ( $post ) {
			$id             = $post->ID;
			$percent        = carbon_get_post_meta( $id, 'promo_code_percent' );
			$res['id']      = intval( $id );
			$res['percent'] = floatval( $percent );
		}

		return $res;
	}

	private function send_error( string $message ): void {
		$this->send_response( [
			'type' => 'error',
			'msg'  => $message,
		] );
	}

	private function send_response( array $response ): void {
		echo json_encode( $response );
		wp_die();
	}

	public static function my_handle_attachment( $file_handler, $post_id = 0, $set_thu = false ): \WP_Error|int {
		if ( $_FILES[ $file_handler ][ carbon_get_theme_option( 'error_string_2' ) ] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
		require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
		require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

		return media_handle_upload( $file_handler, $post_id );
	}
}

Ajax::get_instance();