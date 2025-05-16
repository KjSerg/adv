<?php
add_action( 'wp_ajax_get_exchange_rate', 'get_exchange_rate' );
add_action( 'wp_ajax_nopriv_get_exchange_rate', 'get_exchange_rate' );

function get_exchange_rate() {
	$xml = @simplexml_load_file( 'https://www.tcmb.gov.tr/kurlar/today.xml' );
	if ( ! $xml ) {
		wp_send_json_error( 'TCMB API error' );
	}

	$eurRate = null;
	foreach ( $xml->Currency as $currency ) {
		if ( (string) $currency['CurrencyCode'] === 'EUR' ) {
			$eurRate = floatval( $currency->ForexBuying );
			break;
		}
	}

	if ( $eurRate ) {
		wp_send_json_success( [ 'rate' => round( $eurRate, 4 ) ] );
	}

	wp_send_json_error( 'Rate not found' );
	wp_die();
}


add_action( 'wp_ajax_nopriv_create_order_temp', 'create_order_temp' );
add_action( 'wp_ajax_create_order_temp', 'create_order_temp' );

function create_order_temp() {
	// Розбираємо дані, надіслані через AJAX (ви можете застосувати parse_str, якщо дані надіслані як рядок)
	if ( isset( $_POST['data'] ) ) {
		parse_str( $_POST['data'], $order_data );
	} else {
		$order_data = $_POST;
	}
	$res = array();
	// Збираємо та перевіряємо дані форми
	$user_tour_id   = trim( $_POST['tour_id'] ?? '' );
	$user_tour_name = trim( $_POST['tour_name'] ?? '' );
	$user_start     = $_POST['order_start'] ?? '';
	$user_end       = $_POST['order_end'] ?? '';
	$user_country   = trim( $_POST['country'] ?? '' );
	// İŞLEM TUTARI TUTARI / ((100 - TAKSİT ORANI %) / 100) = TAKSİTLİ TOPLAM TUTAR. Bilginize.
	$sum_eur                   = isset( $_POST['order_sum'] ) ? (float) $_POST['order_sum'] : 0;
	$cart                      = json_decode( stripslashes( $_POST['items'] ?? '[]' ), true );
	$moto                      = json_decode( stripslashes( $_POST['motos'] ?? '[]' ), true );
	$equipment                 = json_decode( stripslashes( $_POST['equipment'] ?? '[]' ), true );
	$postType                  = $_POST['postType'] ?? '';
	$people_count              = $_POST['people_count'] ?? 0;
	$accommodation_count       = $_POST['accommodation_count'] ?? 0;
	$price_tour_start          = $_POST['price_tour_start'] ?? 0;
	$people_count_title        = $_POST['people_count_title'] ?? '';
	$accommodation_count_title = $_POST['accommodation_count_title'] ?? '';


	$xml = @simplexml_load_file( 'https://www.tcmb.gov.tr/kurlar/today.xml' );
	if ( ! $xml ) {
		wp_send_json_error( 'Exchange API error' );
	}

	$eurRate = null;
	foreach ( $xml->Currency as $currency ) {
		if ( (string) $currency['CurrencyCode'] === 'EUR' ) {
			$eurRate = floatval( $currency->ForexBuying );
			break;
		}
	}
	if ( ! $eurRate ) {
		wp_send_json_error( 'Rate not found' );
	}

	// Конвертація
	$total_try = round( $sum_eur * $eurRate, 2 );

	$sum = $total_try;
	// Отримуємо адресу користувача; якщо не задана, встановлюємо дефолтне значення
	$user_address = trim( $_POST['user_address'] ?? '' );
	if ( empty( $user_address ) ) {
		$user_address = 'Not provided';
	}

	$cart_res = [];
	if ( ! empty( $cart ) ) {
		foreach ( $cart as $item ) {
			$cart_res[] = array(
				'name'      => $item['name'] ?? '',
				'country'   => $item['country'] ?? '',
				'phone'     => $item['phone'] ?? '',
				'messenger' => $item['communication'] ?? '',
				'email'     => $item['email'] ?? '',
			);
		}
	}
	$first         = $cart_res[0];
	$first_name    = $first['name'] ?? '';
	$first_country = $first['country'] ?? '';
	$first_phone   = $first['phone'] ?? '';
	$first_email   = $first['email'] ?? '';

	$payment_status = 'failed';

	$post_id = wp_insert_post( [
		'post_status' => 'pending',
		'post_type'   => 'orders',
		'post_title'  => '',
	] );


	if ( $post_id && ! is_wp_error( $post_id ) ) {
		wp_update_post( [
			'ID'         => $post_id,
			'post_title' => $post_id . ' Бронирование - ' . strtoupper( $postType ) . ' ' . $user_tour_name,
		] );
	}

	// Налаштування інтеграції PayTR (отримуємо значення з Carbon Fields або іншого джерела)
	$merchant_id       = carbon_get_theme_option( 'crb_merchant_id' );
	$merchant_key      = carbon_get_theme_option( 'crb_merchant_key' );
	$merchant_salt     = carbon_get_theme_option( 'crb_merchant_salt' );
	$test_mode         = carbon_get_theme_option( 'crb_test_mode' );
	$currency          = carbon_get_theme_option( 'crb_currency_paytr' );
	$merchant_ok_url   = "https://advmania.com.tr/success/?order_id=.'$post_id.'";
	$merchant_fail_url = "https://advmania.com.tr/fail/";

	// Генеруємо унікальний ідентифікатор замовлення
	// $merchant_oid = uniqid();
	$merchant_oid = $post_id;
	// Інші параметри
	$non_3d            = "0";
	$client_lang       = "en";
	$non3d_test_failed = "0";

	// Отримання IP користувача
	if ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	} elseif ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else {
		$ip = $_SERVER["REMOTE_ADDR"];
	}
	$user_ip = $ip;

	$email             = $first_email;
	$payment_amount    = $sum;
	$payment_type      = "card";
	$card_type         = "bonus";
	$installment_count = "0";

	$user_basket = htmlentities( json_encode( array( array( $user_tour_name . $postType, $payment_amount, 1 ), ) ) );
	// Формуємо токен для PayTR
	$hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $installment_count . $currency . $test_mode . $non_3d;
	$token    = base64_encode( hash_hmac( 'sha256', $hash_str . $merchant_salt, $merchant_key, true ) );

	// Подальше опрацювання замовлення: збереження даних, запис до БД, відправка сповіщень тощо.
	global $wpdb;
	$table_name = $wpdb->prefix . 'bookings';

	// Обробка мотоциклів (moto)
	$moto_res = [];
	if ( ! empty( $moto ) ) {
		foreach ( $moto as $item ) {
			$temp       = array(
				'name'  => $item['name'] ?? 'name',
				'price' => $item['price'] ?? 'price',
			);
			$moto_res[] = $temp;
			$wpdb->insert( $table_name, array(
				'start_date'     => $user_start,
				'end_date'       => $user_end,
				'tour_id'        => $user_tour_id,
				'tour_bike_id'   => $item['id'],
				'moto_info'      => json_encode( $temp ),
				'tour_name'      => $user_tour_name,
				'payment_status' => $payment_status,
				'people_details' => json_encode( $cart_res ),
				'tour_amount'    => $sum,
			) );
		}
	}

	// Обробка обладнання (equipment)
	$equipment_res = [];
	if ( ! empty( $equipment ) ) {
		foreach ( $equipment as $item ) {
			$temp            = array(
				'name'  => $item['name'] ?? 'name',
				'price' => $item['price'] ?? 'price',
			);
			$equipment_res[] = $temp;
			$wpdb->insert( $table_name, array(
				'start_date'     => $user_start,
				'end_date'       => $user_end,
				'tour_id'        => $user_tour_id,
				'equipment_id'   => $item['id'],
				'equipment_info' => json_encode( $temp ),
				'tour_name'      => $user_tour_name,
				'payment_status' => $payment_status,
				'people_details' => json_encode( $cart_res ),
				'tour_amount'    => $sum,
			) );
		}
	}

	// Об’єднуємо інформацію для короткого переліку (для email)
	$informLists     = [];
	$informLists_res = [];
	if ( ! empty( $moto_res ) ) {
		$informLists = array_merge( $informLists, $moto_res );
	}
	if ( ! empty( $equipment_res ) ) {
		$informLists = array_merge( $informLists, $equipment_res );
	}
	foreach ( $informLists as $item ) {
		$informLists_res[] = array(
			'info_name' => $item['name'] ?? 'name',
			'price'     => $item['price'] ?? 'price',
		);
	}

	// Зберігаємо дані замовлення через Carbon Fields
	carbon_set_post_meta( $post_id, 'order_products', $cart_res );
	if ( ! empty( $moto_res ) ) {
		carbon_set_post_meta( $post_id, 'order_moto', $moto_res );
	}
	carbon_set_post_meta( $post_id, 'order_order_price', $price_tour_start );
	if ( ! empty( $equipment_res ) ) {
		carbon_set_post_meta( $post_id, 'order_equipment', $equipment_res );
	}
	carbon_set_post_meta( $post_id, 'order_order_tour', $user_tour_name );
	carbon_set_post_meta( $post_id, 'order_order_start', $user_start );
	carbon_set_post_meta( $post_id, 'order_order_end', $user_end );
	carbon_set_post_meta( $post_id, 'order_sum', $sum );
	carbon_set_post_meta( $post_id, 'order_type', $postType );
	carbon_set_post_meta( $post_id, 'order_country', $user_country );
	carbon_set_post_meta( $post_id, 'order_info_test', $moto );
	if ( ! empty( $informLists_res ) ) {
		carbon_set_post_meta( $post_id, 'order_info', $informLists_res );
	}
	carbon_set_post_meta( $post_id, 'order_persons', $people_count_title );
	carbon_set_post_meta( $post_id, 'order_persons_val', $people_count );
	carbon_set_post_meta( $post_id, 'order_accommodation', $accommodation_count_title );
	carbon_set_post_meta( $post_id, 'order_accommodation_val', $accommodation_count );
	if ( $promo_code = filter_input( INPUT_POST, 'promo_code' ) ) {
		carbon_set_post_meta( $post_id, 'order_promo_code', $promo_code );
		if ( $coupon = \ADV\Core\Ajax::get_promo_code( $promo_code ) ) {
			if ( $coupon['id'] > 0 || $coupon['percent'] > 0 ) {
				if ( ! carbon_get_post_meta( $coupon['id'], 'promo_code_order' ) ) {
					carbon_set_post_meta( $post_id, 'order_promo_code_discount', '-' . $coupon['percent'] . '%' );
					carbon_set_post_meta( $post_id, 'order_total_sum', ( $sum - ( $sum * ( $coupon['percent'] / 100 ) ) ) );
					carbon_set_post_meta( $coupon['id'], 'promo_code_order', $post_id );
				}
			}
		}
	}


	// Відправка повідомлення в Telegram
	$lang = pll_current_language();

	$textsTgBot = [
		'en' => [
			'new_order'              => 'New order!',
			'order_start'            => 'Booking from:',
			'order_end'              => 'Booking until:',
			'amount'                 => 'Amount:',
			'order_status'           => 'Payment status:',
			'participants'           => 'Participants:',
			'participants_name'      => 'Name:',
			'participants_country'   => 'Country:',
			'participants_phone'     => 'Phone:',
			'participants_email'     => 'E‑mail:',
			'participants_messenger' => 'Messenger:',
			'moto'                   => 'Moto:',
			'equipment'              => 'Equipment:',
			'product_name'           => 'Name:',
			'product_price'          => 'Price:',
			'total'                  => 'Total:',
		],
		'ru' => [
			'new_order'              => 'Новый заказ!',
			'order_start'            => 'Бронирование с:',
			'order_end'              => 'Бронирование по:',
			'amount'                 => 'Сумма предоплаты:',
			'order_status'           => 'Статус оплаты:',
			'participants'           => 'Участники:',
			'participants_name'      => 'Имя:',
			'participants_country'   => 'Страна:',
			'participants_phone'     => 'Телефон:',
			'participants_email'     => 'E‑mail:',
			'participants_messenger' => 'Мессенджер:',
			'moto'                   => 'Мото:',
			'equipment'              => 'Экипировка:',
			'product_name'           => 'Название:',
			'product_price'          => 'Цена:',
			'total'                  => 'Итого:',
		],
		'tr' => [
			'new_order'              => 'Yeni sipariş!',
			'order_start'            => 'Rezervasyon başlangıcı:',
			'order_end'              => 'Rezervasyon bitişi:',
			'amount'                 => 'Tutar:',
			'order_status'           => 'Ödeme durumu:',
			'participants'           => 'Katılımcılar:',
			'participants_name'      => 'İsim:',
			'participants_country'   => 'Ülke:',
			'participants_phone'     => 'Telefon:',
			'participants_email'     => 'E‑posta:',
			'participants_messenger' => 'Messenger:',
			'moto'                   => 'Moto:',
			'equipment'              => 'Ekipman:',
			'product_name'           => 'Ad:',
			'product_price'          => 'Fiyat:',
			'total'                  => 'Toplam:',
		],
	];

	$current = $textsTgBot[ $lang ] ?? $textsTgBot['en'];


	$message = "{$current['new_order']}\nID — {$post_id}\n";
	$message .= strtoupper( $postType ) . ": {$user_tour_name}\n";
	$message .= "{$current['order_start']} {$user_start}\n";
	$message .= "{$current['order_end']} {$user_end}\n";
	$message .= "{$current['amount']} {$sum} TL\n";
	$message .= "{$current['order_status']} " . ( $payment_status === 'paid' ? ' Paid' : 'Unpaid' ) . "\n\n";
	$message .= "{$current['participants']}\n";

	foreach ( $cart_res as $p ) {
		$message .= "{$current['participants_name']} {$p['name']}\n";
		$message .= "{$current['participants_country']} {$p['country']}\n";
		$message .= "{$current['participants_phone']} {$p['phone']}\n";
		$message .= "{$current['participants_email']} {$p['email']}\n";
		$message .= "{$current['participants_messenger']} {$p['messenger']}\n";
		$message .= "-------------------\n";
	}

	if ( ! empty( $moto_res ) ) {
		$message .= "\n{$current['moto']}\n";
		foreach ( $moto_res as $item ) {
			$message .= "{$current['product_name']} {$item['name']}\n";
			$message .= "{$current['product_price']} {$item['price']}\n-------------------\n";
		}
	}

	if ( ! empty( $equipment_res ) ) {
		$message .= "\n{$current['equipment']}\n";
		foreach ( $equipment_res as $item ) {
			$message .= "{$current['product_name']} {$item['name']}\n";
			$message .= "{$current['product_price']} {$item['price']}\n-------------------\n";
		}
	}

	$tg_bot_token   = carbon_get_theme_option( 'crb_tg_bot' );
	$chat_ids       = carbon_get_theme_option( 'telegram_links' );
	$chat_ids_array = array_map( function ( $item ) {
		return $item['text'];
	}, $chat_ids );

	send_telegram_message( $message, $chat_ids_array, $tg_bot_token );

	// Відправка email повідомлення користувачу
	$to            = $cart_res[0]['email'] ?? 'fear3494@gmail.com';
	$texts         = [
		'en' => [
			'thank_you'     => 'Thank you for your order!',
			'order_details' => 'Order Details',
			'booking_days'  => 'Booking days:',
			'amount'        => 'Amount:',
			'moto'          => 'Additional (Moto)',
			'equipment'     => 'Additional (Equipment)',
			'total'         => 'Total:',
		],
		'ru' => [
			'thank_you'     => 'Спасибо за ваш заказ!',
			'order_details' => 'Детали заказа',
			'booking_days'  => 'Дни бронирования:',
			'amount'        => 'Сумма:',
			'moto'          => 'Дополнительно (Мото)',
			'equipment'     => 'Дополнительно (Оборудование)',
			'total'         => 'Итого:',
		],
		'uk' => [
			'thank_you'     => 'Дякуємо за замовлення!',
			'order_details' => 'Деталі замовлення',
			'booking_days'  => 'Дні бронювання:',
			'amount'        => 'Сума:',
			'moto'          => 'Додатково (Мото)',
			'equipment'     => 'Додатково (Обладнання)',
			'total'         => 'Разом:',
		],
		'tr' => [
			'thank_you'     => 'Siparişiniz için teşekkür ederiz!',
			'order_details' => 'Sipariş Detayları',
			'booking_days'  => 'Rezervasyon günleri:',
			'amount'        => 'Miktar:',
			'moto'          => 'Ekstra (Moto)',
			'equipment'     => 'Ekstra (Ekipman)',
			'total'         => 'Toplam:',
		],
	];
	$current_texts = $texts[ $lang ];
	$subject       = 'Ваш Заказ';
	$email_content = '
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $current_texts['order_details'] . '</title>
        <style>
            * { padding: 0; margin: 0; }
            body { background-color: #DEDCD3; }
            .order-details { width: 600px; margin: 0 auto; background-color: #FFF; }
            h2, h3 { text-align: center; }
            .summary { text-align: right; }
        </style>
    </head>
    <body>
        <table class="order-details">
            <tr>
                <td style="width: 15px;"></td>
                <td><a href="https://adv-mania-wp.web-mosaica.top/"><img src="https://adv-mania-wp.web-mosaica.top/wp-content/uploads/2024/10/cropped-fav-180x180.png" alt="Логотип" style="width: 60px; height: auto;"></a></td>
                <td style="width: 15px;"></td>
            </tr>
            <tr>
                <td style="width: 15px;"></td>
                <td><h2>' . $current_texts['thank_you'] . '</h2></td>
                <td style="width: 15px;"></td>
            </tr>
            <tr>
                <td style="width: 15px;"></td>
                <td><h3>' . $current_texts['order_details'] . '</h3></td>
                <td style="width: 15px;"></td>
            </tr>
            <tr>
                <td style="width: 15px;"></td>
                <td style="width: 570px;">
                    <p><strong>' . strtoupper( $postType ) . ': ' . $user_tour_name . '</strong></p>
                    <p><b>' . $current_texts['booking_days'] . '</b> ' . $user_start . ' - ' . $user_end . '</p>
                    <p><b>' . $current_texts['amount'] . '</b> ' . ( $sum ) . ' TL</p>
                </td>
                <td style="width: 15px;"></td>
            </tr>';
	if ( ! empty( $moto_res ) ) {
		$email_content .= '<tr><td style="width: 15px;"></td><td style="width: 570px;"><h3>' . $current_texts['moto'] . '</h3></td><td style="width: 15px;"></td></tr>';
		foreach ( $moto_res as $item ) {
			$email_content .= '<tr><td style="width: 15px;"></td><td style="width: 570px;">' . $item['name'] . '</td><td style="width: 15px;"></td></tr>';
		}
	}
	if ( ! empty( $equipment_res ) ) {
		$email_content .= '<tr><td style="width: 15px;"></td><td style="width: 570px;"><h3>' . $current_texts['equipment'] . '</h3></td><td style="width: 15px;"></td></tr>';
		foreach ( $equipment_res as $item ) {
			$email_content .= '<tr><td style="width: 15px;"></td><td style="width: 570px;">' . $item['name'] . '</td><td style="width: 15px;"></td></tr>';
		}
	}
	$email_content .= '
            <tr>
                <td style="width: 15px;"></td>
                <td style="width: 235px;"><strong>' . $current_texts['total'] . '</strong></td>
                <td style="width: 235px;" class="summary">' . ( $sum ) . ' TL</td>
                <td style="width: 15px;"></td>
            </tr>
        </table>
    </body>
    </html>';

	add_filter( 'wp_mail_content_type', function () {
		return 'text/html';
	} );
	wp_mail( $to, $subject, $email_content );


	// Якщо інтеграцію з PayTR виконано успішно, повертаємо URL для переадресації
	if ( isset( $payment_redirect_url ) ) {
		$res['payment_url'] = $payment_redirect_url;
	}
	wp_send_json( array(
		'type'              => 'success',
		'id'                => $post_id,
		'merchant_id'       => $merchant_id,
		'user_ip'           => $user_ip,
		'merchant_oid'      => $merchant_oid,
		'email'             => $email,
		'payment_type'      => $payment_type,
		'payment_amount'    => $payment_amount,
		'currency'          => $currency,
		'test_mode'         => $test_mode,
		'non_3d'            => $non_3d,
		'merchant_ok_url'   => $merchant_ok_url,
		'merchant_fail_url' => $merchant_fail_url,
		'user_name'         => $first_name,
		'user_address'      => $first_country,
		'user_phone'        => $first_phone,
		'user_basket'       => $user_basket,
		'debug_on'          => "0",
		'client_lang'       => $client_lang,
		'paytr_token'       => $token,
		'non3d_test_failed' => $non3d_test_failed,
		'installment_count' => $installment_count,
		'card_type'         => $card_type
	) );
	$res['type'] = 'success';
	$res['id']   = $post_id;
	// $res['url'] = get_the_permalink(387) . '?id=' . $post_id;

	wp_send_json( $res );
	die();
}


function send_telegram_message( $message, $chat_ids, $token ) {
	foreach ( $chat_ids as $chat_id ) {
		$url           = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode( $message );
		$response      = file_get_contents( $url );
		$response_data = json_decode( $response, true );
		if ( ! $response_data['ok'] ) {
			error_log( 'Telegram Error for chat_id ' . $chat_id . ': ' . $response_data['description'] );
		}
	}
}

// start add order columns
add_action( 'restrict_manage_posts', 'add_order_filters' );
function add_order_filters() {
	if ( 'orders' === get_post_type() ) {
		$order_types = [ 'tour' => 'Тур', 'bike' => 'Мотоцикл' ];
		echo '<select name="order_type" id="order_type">';
		echo '<option value="">Тип заказа</option>';
		foreach ( $order_types as $value => $label ) {
			echo '<option value="' . esc_attr( $value ) . '"' . selected( $_GET['order_type'] ?? '', $value, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
		$locations = [ 'USA' => 'США', 'Ukraine' => 'Украина', 'Turkey' => 'Турция' ];
		echo '<select name="order_location" id="order_location">';
		echo '<option value="">Страна</option>';
		foreach ( $locations as $value => $label ) {
			echo '<option value="' . esc_attr( $value ) . '"' . selected( $_GET['order_location'] ?? '', $value, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
	}
}

add_filter( 'pre_get_posts', 'filter_orders_by_type_and_location' );
function filter_orders_by_type_and_location( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'orders' !== $query->get( 'post_type' ) ) {
		return;
	}
	$meta_query = $query->get( 'meta_query' ) ?: [];
	if ( ! empty( $_GET['order_type'] ) ) {
		$meta_query[] = [
			'key'     => '_order_type',
			'value'   => sanitize_text_field( $_GET['order_type'] ),
			'compare' => '='
		];
	}
	if ( ! empty( $_GET['order_location'] ) ) {
		$meta_query[] = [
			'key'     => '_order_country',
			'value'   => sanitize_text_field( $_GET['order_location'] ),
			'compare' => '='
		];
	}
	$query->set( 'meta_query', $meta_query );
}

// Додаємо колонки
add_filter( 'manage_orders_posts_columns', 'add_order_columns' );
function add_order_columns( $columns ) {
	$columns['order_type'] = 'Тип заказа';
	$columns['location']   = 'Страна';

	return $columns;
}

// Виведення колонок
add_action( 'manage_orders_posts_custom_column', 'render_order_columns', 10, 2 );
function render_order_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'order_type':
			$order_type = carbon_get_post_meta( $post_id, 'order_type' );
			echo esc_html( $order_type === 'bike' ? 'Мотоцикл' : 'Тур' );
			break;
		case 'location':
			$location = carbon_get_post_meta( $post_id, 'order_country' );
			echo esc_html( $location ?: 'Страна не указана' );
			break;
	}
}

// Додаємо підтримку сортування
add_filter( 'manage_edit-orders_sortable_columns', 'sortable_order_columns' );
function sortable_order_columns( $columns ) {
	$columns['order_type'] = '_order_type';
	$columns['location']   = '_order_country';

	return $columns;
}

// Додаємо обробку сортування
add_action( 'pre_get_posts', 'sort_orders_by_meta' );
function sort_orders_by_meta( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'orders' !== $query->get( 'post_type' ) ) {
		return;
	}
	if ( '_order_type' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_order_type' );
		$query->set( 'orderby', 'meta_value' );
	}
	if ( '_order_country' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_order_country' );
		$query->set( 'orderby', 'meta_value' );
	}
}

// end add order columns
add_action( 'before_delete_post', 'delete_booking_on_order_delete' );
function delete_booking_on_order_delete( $post_id ) {
	if ( get_post_type( $post_id ) === 'orders' ) {
		global $wpdb;
		$booking_info = carbon_get_post_meta( $post_id, 'order_products' );
		if ( ! empty( $booking_info ) ) {
			foreach ( $booking_info as $participant ) {
				$wpdb->delete(
					$wpdb->prefix . 'bookings',
					array( 'tour_bike_id' => $participant['id'] ) // Змінити на правильне поле, якщо потрібно
				);
			}
		}
	}
}

function delete_unpaid_bookings() {
	global $wpdb;
	$threshold      = strtotime( '-10 minutes' ); // Часовий поріг 10 хвилин тому
	$threshold_date = date( 'Y-m-d H:i:s', $threshold );
	$wpdb->query( $wpdb->prepare( "
        DELETE FROM {$wpdb->prefix}bookings 
        WHERE payment_status = 'unpaid' 
        AND created_at < %s
    ", $threshold_date ) );
}

add_action( 'delete_unpaid_bookings_hook', 'delete_unpaid_bookings' );
// Запланувати перевірку, якщо ще не запланована
if ( ! wp_next_scheduled( 'delete_unpaid_bookings_hook' ) ) {
	wp_schedule_event( time(), 'every_ten_minutes', 'delete_unpaid_bookings_hook' );
}
// Додати новий інтервал для 10 хвилин
add_filter( 'cron_schedules', 'add_custom_cron_schedule' );
function add_custom_cron_schedule( $schedules ) {
	$schedules['every_ten_minutes'] = array(
		'interval' => 600, // 10 хвилин у секундах
		'display'  => __( 'Every 10 Minutes' )
	);

	return $schedules;
}

register_deactivation_hook( __FILE__, 'deactivate_delete_unpaid_bookings' );
function deactivate_delete_unpaid_bookings() {
	$timestamp = wp_next_scheduled( 'delete_unpaid_bookings_hook' );
	wp_unschedule_event( $timestamp, 'delete_unpaid_bookings_hook' );
}