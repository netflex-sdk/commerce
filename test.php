<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Netflex\API;
use Dotenv\Dotenv;

use Netflex\Commerce\CartItem;
use Netflex\Commerce\Order;

Dotenv::create(__DIR__)->load();

API::setCredentials(
  getenv('NETFLEX_PUBLIC_KEY'),
  getenv('NETFLEX_PRIVATE_KEY'),
);

$order = Order::retrieveBySession();

if (!$order->cart->items->count()) {
  $order->cart->items[] = CartItem::factory([
    'no_of_entries' => 1,
    'entry_id' => 10027,
    'variant_cost' => 1337,
    'entry_name' => 'Test',
    'tax_percent' => 1.0
  ]);
}

Order::addToSession($order);

dd($order);

dd($order, json_encode($order, JSON_PRETTY_PRINT));
