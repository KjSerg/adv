<?php

namespace ADV\Core;


class CustomCron {
	private static ?self $instance = null;

	public static function get_instance(): self {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function schedule_post_deletion( $post_id, $time = YEAR_IN_SECONDS ): void {
		$post_id = absint( $post_id );
		if ( ! wp_next_scheduled( 'delete_scheduled_post', [ $post_id ] ) ) {
			wp_schedule_single_event( time() + $time, 'delete_scheduled_post', [ $post_id ] );
		}
	}

	public static function cancel_post_deletion( $post_id ): void {
		$post_id   = absint( $post_id );
		$timestamp = wp_next_scheduled( 'delete_scheduled_post', [ $post_id ] );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'delete_scheduled_post', [ $post_id ] );
		}
	}

	private function __construct() {
		$this->initialize_actions();
	}

	private function initialize_actions(): void {
		add_action( 'delete_scheduled_post', [ $this, 'delete_post_by_id' ] );
	}

	public function delete_post_by_id( $post_id ): void {
		if ( get_post( $post_id ) ) {
			wp_delete_post( $post_id );
		}
	}

}

CustomCron::get_instance();