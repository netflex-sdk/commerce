<?php

require_once(__DIR__ . '/vendor/autoload.php');

date_default_timezone_set('Europe/Oslo');

use Netflex\API;
use Dotenv\Dotenv;

use Netflex\Commerce\CartItem;
use Netflex\Commerce\LogItem;
use Netflex\Commerce\Order;

use Illuminate\Container\Container;
use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Cache;

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

Dotenv::create(__DIR__)->load();

API::setCredentials(
  getenv('NETFLEX_PUBLIC_KEY'),
  getenv('NETFLEX_PRIVATE_KEY'),
);

// Cache test setup
$container = new Container;
$container['config'] = [
  'cache.default' => 'file',
  'cache.stores.file' => [
    'driver' => 'file',
    'path' => __DIR__ . '/cache'
  ]
];
$container['files'] = new Filesystem;
$container->singleton('cache', function ($app) {
  return (new CacheManager($app))->store();
});
Facade::setFacadeApplication($container);

/**
 * -------------------------------------------------------
 */

// Creating new order and checking out
$order = Order::create()
  ->addCart([
    'entry_id' => 10001,
    'entry_name' => 'Ticket',
    'variant_cost' => 100,
    'no_of_entries' => 1,
    'tax_percent' => 1.12,
    'properties' => [
      'someCustomKey' => 'someCustomValue'
    ]
  ])
  ->checkout([
    'firstname' => 'Ola',
    'surname' => 'Nordmann'
  ])
  ->save([
    'status' => 'p',
    'currency' => 'NOK',
    'customer_mail' => 'ola@nordmann.no',
    'customer_phone' => '99123456'
  ])
  ->addData('paymentId', '123456789', 'Payment ID')
  ->addLog('Customer sent to payment');

// Adding payment and completing order
$order
  ->addLog('Customer returned from payment')
  ->addPayment([
    'payment_method' => 'stripe',
    'amount' => 100,
    'status' => 'OK',
    'capture_status' => 'OK',
    'transaction_id' => '123456789',
    'card_type_name' => 'visa',
    'data' => [
      'someCustomKey' => 'someCustomValue'
    ]
  ])
  ->register()
  ->lock()
  ->refresh();

dd($order);
