<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Netflex\API;
use Dotenv\Dotenv;

use Netflex\Commerce\Order;

Dotenv::create(__DIR__)->load();

API::setCredentials(
  getenv('NETFLEX_PUBLIC_KEY'),
  getenv('NETFLEX_PRIVATE_KEY'),
);

$order = Order::create();

dd($order, json_encode($order, JSON_PRETTY_PRINT));
