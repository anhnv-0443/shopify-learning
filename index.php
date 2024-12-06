<?php
// php -S 127.0.0.1:10000

include_once('includes/mysql.php');
include_once('includes/shopify.php');

$parameters = $_GET;
$shopUrl = $parameters['shop'] ?? '';
if (!$shopUrl) {
    echo 'Shop URL is required';
    exit();
}

$selectQuery = "SELECT * FROM shops WHERE shop_url = '$shopUrl'";
$result = $mysql->query($selectQuery);
if ($result->num_rows) {
    $data = $result->fetch_assoc();
    $shopify = new Shopify($shopUrl, $data['access_token']);

    // https://shopify.dev/docs/api/admin-rest/2024-10/resources/shop#show-2024-10
    $response = $shopify->reset_api('/admin/api/2024-10/shop.json', [], 'GET');
    if (isset($response['errors'])) {
        header('Location: install.php?shop=' . $_GET['shop']);
    } else {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    header('Location: install.php?shop=' . $_GET['shop']);
}

exit();