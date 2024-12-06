<?php

include_once('includes/mysql.php');
include_once('includes/constant.php');

$parameters = $_GET;
$hmac = $parameters['hmac'];
$parameters = array_diff_key($parameters, ['hmac' => '']);
ksort($parameters);

$new_hmac = hash_hmac('sha256', http_build_query($parameters), $_SECTERT_KET);

if (hash_equals($hmac, $new_hmac)) {
    $access_token_endpoint = 'https://' . $parameters['shop'] . '/admin/oauth/access_token';
    $var = [
        'client_id' => $_API_KEY,
        'client_secret' => $_SECTERT_KET,
        'code' => $parameters['code']
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($var));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($var));
    $response = curl_exec($ch);
    curl_close($ch);

    $shopUrl = $parameters['shop'];
    $accessToken = json_decode($response, true)['access_token'];
    $selectQuery = "SELECT * FROM shops WHERE shop_url = '$shopUrl'";
    if ($mysql->query($selectQuery)->num_rows) {
        $updateQuery = "UPDATE shops SET access_token = '$accessToken', hmac = '$hmac', updated_at = NOW() WHERE shop_url = '$shopUrl'";
        if ($mysql->query($updateQuery)) {
            echo "<script>window.location = 'https://" . $parameters['shop'] . "/admin/apps'</script>";
            die();
        }
    } else {
        $query = "INSERT INTO shops (shop_url, access_token, hmac, created_at, updated_at) VALUES ('$shopUrl', '$accessToken', '$hmac', NOW(), NOW())";
        if ($mysql->query($query)) {
            header('Location: https://' . $parameters['shop'] . '/admin/apps');
            exist();
        }
    }
} else {
    echo 'HMAC is invalid';
}


// echo var_dump($parameters);

