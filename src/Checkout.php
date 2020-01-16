<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

/**
 * @property string $firstname
 * @property string $surname
 * @property string $company
 * @property string $address
 * @property string $postal
 * @property string $city
 * @property string $shipping_firstname
 * @property string $shipping_surname
 * @property string $shipping_company
 * @property string $shipping_address
 * @property string $shipping_postal
 * @property string $shipping_city
 * @property float $shipping_cost
 * @property float $shipping_tax
 * @property float $shipping_total
 * @property float $expedition_cost
 * @property float $expedition_tax
 * @property float $expedition_total
 * @property string $checkout_start
 * @property string $checkout_end
 * @property string $shipping_tracking_code
 * @property string $shipping_tracking_url
 * @property string $ip
 * @property string $user_agent
 * @property string $updated
 */
class Checkout extends ReactiveObject
{
  protected $defaults = [
    'checkout_start' => null,
    'checkout_end' => null,
    'firstname' => null,
    'surname' => null,
    'company' => null,
    'address' => null,
    'postal' => null,
    'city' => null,
    'shipping_firstname' => null,
    'shipping_surname' => null,
    'shipping_company' => null,
    'shipping_address' => null,
    'shipping_postal' => null,
    'shipping_city' => null,
    'shipping_cost' => 0.0,
    'shipping_tax' => 0.0,
    'shipping_total' => 0.0,
    'expedition_cost' => 0.0,
    'expedition_tax' => 0.0,
    'expedition_total' => 0.0,
    'user_agent' => null,
    'shipping_tracking_code' => null,
    'shipping_tracking_url' => null,
    'ip' => null,
    'updated' => null,
  ];
}
