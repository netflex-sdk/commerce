<p align="center">
<a href="https://packagist.org/packages/netflex/commerce/stats"><img src="https://img.shields.io/packagist/dm/netflex/commerce" alt="Downloads"></a>
<a href="https://packagist.org/packages/netflex/commerce"><img src="https://img.shields.io/packagist/v/netflex/commerce?label=stable" alt="Stable version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/netflex-sdk/sdk.svg" alt="License: MIT"></a>
</p>

# Netflex Commerce SDK
The Netflex Commerce SDK is a library for working with the commerce endpoints in the Netflex API.

## Contributing
Thank you for considering contributing to the Netflex Commerce SDK! Please read the [contribution guide](CONTRIBUTING.md).

## Code of Conduct
In order to ensure that the community is welcoming to all, please review and abide by the [Code of Conduct](CODE_OF_CONDUCT.md).

## License
The Netflex Commerce SDK is open-sourced software licensed under the [MIT license](LICENSE).

## Installation

```bash
composer require netflex/commerce
```

## Standalone setup
If you are using this library standalone, you need to specify API keys in the .env file and set them in the API client.

```php
Dotenv::create(__DIR__)->load();

API::setCredentials(
  getenv('NETFLEX_PUBLIC_KEY'),
  getenv('NETFLEX_PRIVATE_KEY'),
);
```

If you are using this library with the core Netflex SDK, this should already be set.

## Getting started TL;DR
```php
// Creating new order and checking out
$order = Order::create()
  ->addToSession()
  ->addCart([
    'entry_id' => 10001,
    'entry_name' => 'Ticket',
    'variant_cost' => 100,
    'no_of_entries' => 1,
    'tax_percent' => 1.12
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
$order = Order::retrieveBySecret('a72b...12f4')
  ->addLog('Customer returned from payment')
  ->addPayment([
    'payment_method' => 'stripe',
    'amount' => 100,
    'status' => 'OK',
    'capture_status' => 'OK',
    'transaction_id' => '123456789',
    'card_type_name' => 'visa',
  ])
  ->register()
  ->lock()
  ->removeFromSession();
```

## Getting started properly
Always start with an Order object. Order is the main class of this library, meant to hold all other objects as children.

```php
// Empty object. Does NOT create an order in the API.
$order = new Order();

// Creating and getting a new empty order in the API.
$order = Order::create();

// Retrieves an existing order from the API based on an order id. Throws an exception if not found.
$order = Order::retrieve(10001);

// Retrieves an existing order from the API based on a register id. Throws an exception if not found.
$order = Order::retrieveByRegisterId(10001);

// Retrieves an existing order from the API based on an order secret. Throws an exception if not found.
$order = Order::retrieveBySecret('a1234567896e8bf63bbd43e851811234');

// Retrieves an existing order from the API based on an order secret stored in $_SESSION.
// If session or order does not exist, it creates an empty object. It does NOT create a new empty order in the API.
// On the next save() or refresh(), it stores the order secret in session.
$order = Order::retrieveBySession();

// Retrieves an existing order from the API based on an order secret stored in $_SESSION.
// If session or order does not exist, it creates a new empty order in the API and stores the order secret in session.
$order = Order::retrieveBySessionOrCreate();

// Manually adding the order secret to session.
$order->addToSession();
```

## Adding things to the order
On all add methods below, the data is immediately sent to the API.
To update the order object with added items and calculated totals, you need to call the refresh() method.

```php
$order->addCart([
  'entry_id' => 10001,
  'entry_name' => 'Ticket',
  'variant_cost' => 100,
  'no_of_entries' => 1,
  'tax_percent' => 1.12
]);

$order->addLog('This is a log item');

$order->addLogInfo('Log some info');
$order->addLogWarning('Log a warning');
$order->addLogSuccess('Log a success');
$order->addLogDanger('Log danger');

$order->addData('key', 'value', 'Label');

$order->addDiscount([
  'scope' => 'item', // cart|item|shipping
  'scope_key' => '10001', // cart item id
  'label' => '20 % discount on your ticket',
  'discount' => 0.20,
  'type' => 'percent', // percent|fixed|amount
]);

$order->addPayment([
  'payment_method' => 'stripe',
  'amount' => 100,
  'status' => 'OK',
  'capture_status' => 'OK',
  'transaction_id' => '123456789',
  'card_type_name' => 'visa',
]);

// Updating the order object with added items and calculated totals
$order->refresh();
```

## Updating the order
Updating the properties on the main order object.
Option A:
```php
$order->status = 'p';
$order->currency = 'NOK';
$order->customer_mail = 'ola@nordmann.no';
$order->customer_phone = '99123456';
$order->save();
```

Option B:
```php
$order->save([
  'status' => 'p',
  'currency' => 'NOK',
  'customer_mail' => 'ola@nordmann.no',
  'customer_phone' => '99123456'
]);
```

Updating a cart item.
Option A:
```php
// Incrementing the number of entries in an item, with a specific entry_id
foreach ($order->cart->items as $item) {
  if ($item->entry_id == 10001) {
    $item->no_of_entries = 5;
    $item->save();
  }
}
```
Option B:
```php
foreach ($order->cart->items as $item) {
  if ($item->entry_id == 10001) {
    $item->save(['no_of_entries' => 5]);
  }
}
```

## Checking out and completing the order
```php
$order->checkout([
  'firstname' => 'Ola',
  'surname' => 'Nordmann'
]);

$order->register();

$order->saveStatus('n');

$order->checkoutEnd();

// This does the same as saveStatus('n') and checkoutEnd();
// Saves the status to 'n' and add a checkout_end date.
$order->lock();

$order->removeFromSession();

```
<hr>

Copyright &copy; 2009-2020 **[Apility AS](https://apility.no)**
