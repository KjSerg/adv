<?php


namespace ADV\Core;

class Admin {
	public function add_admin_columns(): void {
		add_filter( 'manage_edit-promocode_columns', [ $this, 'add_promocode_columns' ], 10, 1 );
		add_action( 'manage_posts_custom_column', [ $this, 'fill_post_columns' ], 10, 1 );
	}

	public function add_promocode_columns( $my_columns ) {
		$my_columns['discount'] = 'Скидка, %';
		$my_columns['user']     = 'Пользователь';
		$my_columns['email']    = 'Email';
		$my_columns['order_id'] = 'ID заказа';

		return $my_columns;
	}

	public function fill_post_columns( $column ): void {
		global $post;
		$ID        = $post->ID;
		$post_type = get_post_type( $ID );
		$discount  = '';
		$user      = '';
		$email     = '';
		$order_id  = '';
		if ( $post_type === 'promocode' ) {
			$discount = carbon_get_post_meta( $ID, 'promo_code_percent' );
			$user     = carbon_get_post_meta( $ID, 'promo_code_user_name' ) . ' ' . carbon_get_post_meta( $ID, 'promo_code_user_country' );
			$email    = carbon_get_post_meta( $ID, 'promo_code_user_email' );
			$order_id = carbon_get_post_meta( $ID, 'promo_code_order' );
		}
		switch ( $column ) {
			case 'discount':
				echo $discount;
				break;
			case 'user':
				echo $user;
				break;
			case 'email':
				echo $email;
				break;
			case 'order_id':
				echo $order_id;
				break;
		}
	}
}

$admin = new Admin();
$admin->add_admin_columns();