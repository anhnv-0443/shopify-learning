<?php

$server = 'localhost';
$username = 'root';
$password = 'Password123#@!';
$database_name = 'shopify_app';

$mysql = mysqli_connect($server, $username, $password, $database_name);
if (!$mysql) {
    die('Connection failed: ' . mysqli_connect_error());
}