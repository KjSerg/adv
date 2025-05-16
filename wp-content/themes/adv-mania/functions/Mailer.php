<?php

namespace ADV\Components;

class Mailer {

	public static function adopt( $text ): string {
		return '=?UTF-8?B?' . base64_encode( $text ) . '?=';
	}

	public static function send_promo( $_id ) {
		if ( ! $email = carbon_get_post_meta( $_id, 'promo_code_user_email' ) ) {
			return false;
		}
		$c            = true;
		$message      = '';
		$project_name = get_bloginfo( 'name' );
		$var          = variables();
		$set          = $var['setting_home'];
		$url          = $var['url'];
		$form_subject = 'Your promo code';
		$string       = 'Coupon';
		$t            = get_the_title( $_id );
		$message      .= "
		" . ( ( $c = ! $c ) ? '<tr>' : '<tr style="background-color: #f8f8f8;">' ) . "
		<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$string</b></td>
		<td style='padding: 10px; border: #e9e9e9 1px solid;'>$t</td>
		</tr>
		";
		$message      = "<table style='width: 100%;'>$message</table>";
		$headers      = "MIME-Version: 1.0" . PHP_EOL .
		                "Content-Type: text/html; charset=utf-8" . PHP_EOL .
		                'From: ' . self::adopt( $project_name ) . ' <info@' . $_SERVER['HTTP_HOST'] . '>' . PHP_EOL .
		                'Reply-To: ' . $email . '' . PHP_EOL;

		return wp_mail( $email, $form_subject, $message, $headers );
	}
}


