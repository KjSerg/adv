<?php
// callback_url_sample.php

// Отримуємо дані з POST
$post = $_POST;

// Налаштування (замініть на свої реальні дані)
$merchant_key  = 'bQF2WM6Ln2pqYNKs';
$merchant_salt = 'h7y2AsoqpYMi6Zd2';

// Формуємо хеш згідно з алгоритмом PayTR:
// Конкатенуємо: merchant_oid, merchant_salt, status, total_amount
$hash_str = $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'];
$hash = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

// Перевірка хешу
if($hash != $post['hash']) {
    die('PAYTR notification failed: bad hash');
}

// Обробка callback: якщо статус "success", виконайте необхідні дії
if($post['status'] == 'success'){
    // Наприклад, оновіть статус замовлення у базі даних, надішліть повідомлення, тощо.
} else {
    // Обробка помилки платежу
}

echo "OK";
exit;
?>
