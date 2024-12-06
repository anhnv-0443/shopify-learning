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

    $products = $shopify->reset_api('/admin/api/2021-07/products.json', [], 'GET');
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    header('Location: install.php?shop=' . $_GET['shop']);
}

exit();