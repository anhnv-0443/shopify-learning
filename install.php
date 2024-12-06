<?php

include_once('includes/constant.php');

$shop = $_GET['shop'];
// $scopes = 'read_products,write_products,read_customers,write_customers,read_orders,write_orders,read_draft_orders,write_draft_orders,read_inventory,write_inventory,read_locations,read_script_tags,write_script_tags,read_fulfillments,write_fulfillments,read_shipping,write_shipping,read_analytics,read_checkouts,write_checkouts,read_reports,write_reports,read_price_rules,write_price_rules,read_marketing_events,write_marketing_events,read_resource_feedbacks,write_resource_feedbacks,read_shopify_payments_payouts,read_shopify_payments_disputes,read';
$scopes = 'read_products,write_products,read_customers,write_customers,read_orders,write_orders';
$redirect_uri = $_NGROK_URL . '/token.php';
$nonce = bin2hex(random_bytes(12));
$access_mode = 'pre-user';

$oauth_url = 'https://' . $shop . '/admin/oauth/authorize?client_id=' . $_API_KEY . '&scope=' . $scopes . '&redirect_uri=' . $redirect_uri . '&state=' . $nonce . '&grant_options[]=' . $access_mode;

header('Location: ' . $oauth_url);
exit();