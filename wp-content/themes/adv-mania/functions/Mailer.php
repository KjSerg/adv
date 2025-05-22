<?php

namespace ADV\Components;

class Mailer {

	public static function adopt( $text ): string {
		return '=?UTF-8?B?' . base64_encode( $text ) . '?=';
	}

	public static function get_default_promo_mail( $_id ): string {
		$c       = true;
		$string  = 'Coupon';
		$t       = get_the_title( $_id );
		$message = "
		" . ( ( $c = ! $c ) ? '<tr>' : '<tr style="background-color: #f8f8f8;">' ) . "
		<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$string</b></td>
		<td style='padding: 10px; border: #e9e9e9 1px solid;'>$t</td>
		</tr>
		";

		return "<table style='width: 100%;'>$message</table>";
	}

	private static function get_variables( $_id ): array {
		return [
			get_the_title( $_id ) ?: '',
			carbon_get_post_meta( $_id, 'promo_code_percent' ) ?: '',
			carbon_get_post_meta( $_id, 'promo_code_user_name' ) ?: '',
			carbon_get_post_meta( $_id, 'promo_code_user_country' ) ?: '',
			carbon_get_post_meta( $_id, 'promo_code_user_email' ) ?: '',
		];
	}

	public static function substitution_variables( $html, $variables ): string {
		$find = array(
			'$%coupon%',
			'$%coupon_discount%',
			'$%coupon_name%',
			'$%coupon_country%',
			'$%coupon_email%',
		);

		return str_replace( $find, $variables, $html );
	}

	public static function get_promo_code_mail_id() {
		$mail_id = 0;
		if ( $mail = carbon_get_theme_option( 'promo_code_mail' ) ) {
			error_log('pll_current_language '. pll_current_language());
			$mail_id = $mail[0]['id'];
			if ( function_exists( 'pll_get_post' ) ) {
				$mail_id = pll_get_post( $mail_id, pll_current_language() ) ?: $mail_id;
			}
			error_log('$mail_id '. $mail_id);
			if ( get_post( $mail_id ) ) {
				return $mail_id;

			}
		}

		return $mail_id;
	}

	public static function send_promo( $_id ) {
		if ( ! $email = carbon_get_post_meta( $_id, 'promo_code_user_email' ) ) {
			return false;
		}
		$message      = '';
		$form_subject = 'Your promo code';
		if ( $mail_id = self::get_promo_code_mail_id() ) {
			$form_subject = get_the_title( $mail_id );
			$message      = self::substitution_variables( get_content_by_id( $mail_id ), self::get_variables( $_id ) );
		}
		$message      = $message ?: self::get_default_promo_mail( $_id );
		$project_name = get_bloginfo( 'name' );
		$var          = variables();
		$set          = $var['setting_home'];
		$url          = $var['url'];
		$headers      = "MIME-Version: 1.0" . PHP_EOL .
		                "Content-Type: text/html; charset=utf-8" . PHP_EOL .
		                'From: ' . self::adopt( $project_name ) . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>' . PHP_EOL .
		                'Reply-To: ' . $email . '' . PHP_EOL;

		return wp_mail( $email, $form_subject, $message, $headers );
	}


}


