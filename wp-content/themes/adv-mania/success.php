<?php
get_header();
/*
 * Template name: success
 * */

// Callback від платіжної системи (POST-запит)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка наявності необхідних даних
    if ( !isset($_POST['merchant_oid'], $_POST['status'], $_POST['total_amount'], $_POST['hash']) ) {
        die('Не отримано всіх необхідних даних від платіжної системи.');
    }
    
    $post_data = $_POST;
    $merchant_key   = carbon_get_theme_option('crb_merchant_key');
    $merchant_salt  = carbon_get_theme_option('crb_merchant_salt');
    
    // Обчислення хешу
    $computed_hash = base64_encode(hash_hmac('sha256', $post_data['merchant_oid'] . $merchant_salt . $post_data['status'] . $post_data['total_amount'], $merchant_key, true));
    
    if($computed_hash !== $post_data['hash']) {
        die('PAYTR notification failed: bad hash');
    }
    
    // Отримуємо ID замовлення за merchant_oid (переконайтеся, що функція get_order_id_by_merchant_oid() визначена)
    $order_id = get_order_id_by_merchant_oid($post_data['merchant_oid']);
    if(!$order_id) {
        die('Не вдалося знайти замовлення за merchant_oid.');
    }
    
    if($post_data['status'] === 'success'){
        wp_update_post(array(
            'ID'          => $order_id,
            'post_status' => 'publish'
        ));
    } else {
        wp_update_post(array(
            'ID'          => $order_id,
            'post_status' => 'failed'
        ));
    }
    
    // Відповідаємо платіжній системі, що повідомлення отримано
    echo "OK";
    exit;
} 
// Виведення статусу замовлення (GET-запит із order_id)
elseif ( isset($_GET['order_id']) ) {
    $order_id = intval($_GET['order_id']);
    $order = get_post($order_id);
    
    if (!$order) {
        echo "<section class='booking'><div class='container'><div class='step-inner'><p>Замовлення не знайдено.</p></div></div></section>";
        get_footer();
        exit;
    }
    
    $order_status = $order->post_status;
    $order_status = get_post_status( $order_id );

        // Якщо статус ще не “publish” — робимо публікацію
        if ( $order_status !== 'publish' ) {
            wp_update_post([
                'ID'          => $order_id,
                'post_status' => 'publish',
            ]);
            $order_status = 'publish';
        }
    if ( $order_status === 'publish' && ! get_post_meta( $order_id, '_tg_notified', true ) ) {
        $post_title = get_the_title( $order_id );
        $post_title = html_entity_decode( get_the_title( $order_id ), ENT_QUOTES, 'UTF-8' );
        $post_title = preg_replace( '/[\x{2013}\x{2014}]/u', '-', $post_title );

        $sum        = carbon_get_post_meta( $order_id, 'order_sum' );
    
        $message  = "✅ Новый заказ оплачен!\n";
        $message .= "{$post_title}\n";
        $message .= "Сумма предоплаты: {$sum} TRY";
    
        // $bot_token = '7462466820:AAHOGYJmFeJ1NSbAPTzaddeZoHUzRFFmMSo';
        // $chat_ids  = [ '504878177' ];
        $bot_token = carbon_get_theme_option('crb_tg_bot');
        $chat_ids  = array_column( carbon_get_theme_option('telegram_links'), 'text' );
        send_telegram_message( $message, $chat_ids, $bot_token );
    
        update_post_meta( $order_id, '_tg_notified', '1' );
    }
    ?>
    <section class="booking">
        <div class="container">
            <div class="step-inner">
                <div class="step-item active" data-step="step3">
                    <div class="success">
                        <div class="success-item">
                            <div class="success-item__media">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="80" height="80" rx="40" fill="#E7343F"></rect>
                                    <path d="M35.7874 45.4338L50.4029 31.1724C50.7478 30.8359 51.1502 30.6676 51.6101 30.6676C52.07 30.6676 52.4724 30.8359 52.8173 31.1724C53.1622 31.509 53.3346 31.9086 53.3346 32.3714C53.3346 32.8341 53.1622 33.2338 52.8173 33.5703L36.9946 49.0517C36.6496 49.3882 36.2473 49.5565 35.7874 49.5565C35.3275 49.5565 34.9251 49.3882 34.5802 49.0517L27.1646 41.8158C26.8197 41.4793 26.6545 41.0796 26.6688 40.6169C26.6832 40.1541 26.8628 39.7545 27.2078 39.4179C27.5527 39.0814 27.9622 38.9131 28.4365 38.9131C28.9107 38.9131 29.3203 39.0814 29.6652 39.4179L35.7874 45.4338Z" fill="white"></path>
                                </svg>
                            </div>
                            <div class="success-item__desc">
                                <h4>Successfully Completed</h4>
                                <p>Your booking has been <?php echo $order_status; ?>.</p>
                                <p>Thank you for choosing our services. We have sent a confirmation email with all the details of your booking.</p>
                            </div>
                            <div class="success-item__link">
                                <a href="/" class="btn btn-red">Main</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
} 
// Якщо ні POST, ні GET з order_id – повідомляємо про відсутність даних
else {
    echo "<section class='booking'><div class='container'><div class='step-inner'><p>Не отримано даних.</p></div></div></section>";
}
get_footer();
?>
