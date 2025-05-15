<?php
/*
 * Template name: test pay tr
 *
 * Обов’язкові поля для Direct API PayTR:
 *  - merchant_id, user_ip, merchant_oid, email, payment_type, payment_amount, currency,
 *    test_mode, cc_owner, card_number, expiry_month, expiry_year, cvv, merchant_ok_url,
 *    merchant_fail_url, client_lang, no_installment, max_installment, user_basket,
 *    user_name, user_address, user_phone, paytr_token, installment_count, card_type,
 *    non3d_test_failed.
 */


// Налаштування PayTR (замініть на свої реальні дані)
// $merchant_id   = '551142';
// $merchant_key  = 'bQF2WM6Ln2pqYNKs';
// $merchant_salt = 'h7y2AsoqpYMi6Zd2';
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sample Payment Form</title>
</head>
<body>

<div>
    <h1>Sample Payment Form</h1>
    <p>STEP 1 Sample Codes</p>
</div>
<br><br>

<div style="width: 100%;margin: 0 auto;display: table;">

    <?php

    $merchant_id   = '551142';
    $merchant_key  = 'bQF2WM6Ln2pqYNKs';
    $merchant_salt = 'h7y2AsoqpYMi6Zd2';

    $email = "testnon3d@paytr.com";
    $payment_amount = "1550";
    $merchant_oid = uniqid();
    $user_name = "name";
    $user_address = "TEST TEST ADDRESS";
    $user_phone = "05555555";
    $merchant_ok_url = "http://www.example.com/success.php";
    $merchant_fail_url = "http://www.example.com/error.php";
    $user_basket = "";

    $user_basket = base64_encode(json_encode(array(
        array("Sample Product 11", "18.00", 1), 
        array("Sample Product 22", "33.25", 2), 
        array("Sample Product 33", "45.42", 1)  
    )));

    if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    $user_ip=$ip;
    $timeout_limit = "30";
    $debug_on = 1;
    $test_mode = 1;
    $no_installment = 0; 
    $max_installment = 0;
    $currency = "TL";

    $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
    $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
    $post_vals=array(
            'merchant_id'=>$merchant_id,
            'user_ip'=>$user_ip,
            'merchant_oid'=>$merchant_oid,
            'email'=>$email,
            'payment_amount'=>$payment_amount, 
            'paytr_token'=>$paytr_token,
            'user_basket'=>$user_basket,
            'debug_on'=>$debug_on,
            'no_installment'=>$no_installment,
            'max_installment'=>$max_installment,
            'user_name'=>$user_name,
            'user_address'=>$user_address,
            'user_phone'=>$user_phone,
            'merchant_ok_url'=>$merchant_ok_url,
            'merchant_fail_url'=>$merchant_fail_url,
            'timeout_limit'=>$timeout_limit,
            'currency'=>$currency,
            'test_mode'=>$test_mode
        );

    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $result = @curl_exec($ch);

    if(curl_errno($ch))
        die("PAYTR IFRAME connection error. err:".curl_error($ch));

    curl_close($ch);

    $result=json_decode($result,1);

    if($result['status']=='success')
        $token=$result['token'];
    else
        die("PAYTR IFRAME failed. reason:".$result['reason']);

    ?>

  <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
  <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $token;?>" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
    <script>iFrameResize({},'#paytriframe');</script>

</div>

<br><br>
</body>
</html>