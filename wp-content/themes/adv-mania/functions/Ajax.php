<?php

namespace ADV\Core;


use ADV\Components\Mailer;
use Exception;

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

		add_action( 'wp_ajax_nopriv_create_promo_code', [ $this, 'create_promo_code' ] );
		add_action( 'wp_ajax_create_promo_code', [ $this, 'create_promo_code' ] );

	}


	public function create_promo_code(): void {
		$res   = [];
		$nonce = filter_input( INPUT_POST, 'true_nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'create_promo_code' ) ) {
			$this->send_error( 'Error creating promo code' );
		}
		$name    = filter_input( INPUT_POST, 'name' );
		$country = filter_input( INPUT_POST, 'country' );
		$email   = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );
		if ( ! $name || ! $country || ! $email ) {
			$this->send_error( 'Error creating promo code' );
		}
		$user_ip  = $_SERVER['REMOTE_ADDR'];
		$attempts = get_transient( "reg_attempts_$user_ip" );
		if ( $attempts && $attempts > 2 ) {
			$this->send_error( 'Too many attempts, please wait a few minutes.' );
		}
		set_transient( "reg_attempts_$user_ip", ( $attempts ? $attempts + 1 : 1 ), 60 * 2 );
		try {
			$unique_code = self::generate_unique_promo_code( 5 );
			$post_data   = array(
				'post_type'   => 'promocode',
				'post_title'  => $unique_code,
				'post_status' => 'publish'
			);
			$_id         = wp_insert_post( $post_data );
			$post        = get_post( $_id );
			if ( $post ) {
				carbon_set_post_meta( $_id, 'promo_code_user_name', $name );
				carbon_set_post_meta( $_id, 'promo_code_user_country', $country );
				carbon_set_post_meta( $_id, 'promo_code_user_email', $email );
				carbon_set_post_meta( $_id, 'promo_code_percent', carbon_get_theme_option( 'promo_codes_percent' ) ?: 10 );
				CustomCron::schedule_post_deletion($_id);
				$msg = pll__( 'Ваш код' ) . ' <br>' . $post->post_title;
				if ( $is_send = Mailer::send_promo( $_id ) ) {
					$msg .= ' <br>' . pll__( 'отправлено на email' );
				}
				$msg .= ' <br>' . pll__( 'Срок действия кода 12 мес.' );
				$this->send_response( [
					'type'     => 'success',
					'msg'      => $msg,
					'$is_send' => $is_send,
				] );
			} else {
				$this->send_error( 'Error' );
			}
		} catch ( Exception $e ) {
			$this->send_error( $e->getMessage() );
		}
	}

	/**
	 * @throws Exception
	 */
	public static function generate_unique_promo_code( $length = 10, $max_attempts = 10 ): string {
		$attempt = 0;
		do {
			$code   = self::generate_random_string( $length );
			$coupon = self::get_promo_code( $code );
			if ( $coupon['id'] === 0 && $coupon['percent'] === 0 ) {
				return $code;
			}
			$attempt ++;
			if ( $attempt >= $max_attempts ) {
				throw new Exception( 'Не удалось сгенерировать уникальный промокод после ' . $max_attempts . ' попыток' );
			}

		} while ( true );
	}

	public static function generate_random_string( $length = 10 ): string {
		$characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$randomString .= $characters[ random_int( 0, $charactersLength - 1 ) ];
		}

		return $randomString;
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
			$id      = $post->ID;
			$percent = carbon_get_post_meta( $id, 'promo_code_percent' );
			$order   = carbon_get_post_meta( $id, 'promo_code_order' );
			if ( ! $order ) {
				$res['id']      = intval( $id );
				$res['percent'] = floatval( $percent );

			}
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