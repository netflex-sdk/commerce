<?php

namespace Netflex\Commerce\Traits\API;

/* use Exception; */

use Netflex\API;
use Netflex\Commerce\Order;

/* use Netflex\Commerce\Exceptions\OrderNotFoundException; */

trait Orders
{
  public function save () {
    $payload = [];
    $client = API::getClient();

    if (!$this->id) {
      $this->attributes['id'] = $client
        ->post(trim(static::$base_path, '/'), $payload)
        ->order_id;
    } else {
      if (count($this->modified)) {
        $client->put(trim(static::$base_path, '/') . '/' . $this->id, $payload);
      }
    }

    $this->refresh();
  }

  public function refresh () {
    $this->attributes = API::getClient()
      ->get(trim(static::$base_path, '/') . '/' . $this->id, true);

    return $this;
  }

  /**
   * @param string $secret
   * @return static
   */
  public static function retrieveBySecret($secret)
  {
    return new static(
      API::getClient()
        ->get(trim(static::$base_path, '/') . '/secret/' . $secret)
    );
  }

  public static function removeFromSession ($key = 'netflex_cart') {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION[$key] = null;
  }

  public static function addToSession (Order $order, $key = 'netflex_cart') {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION[$key] = $order->secret;
  }

  public static function retrieveBySession ($key = 'netflex_cart') {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if (isset($_SESSION[$key])) {
      return static::retrieveBySecret($_SESSION[$key]);
    }

    return new static();
  }

  /**
   * Creates empty order object based on orderData
   *
   * @param array $order
   * @return static
   */
  public static function create($order = [])
  {
    return static::retrieve(
      API::getClient()
        ->post(trim(static::$base_path, '/'), $order)
        ->order_id
    );
  }
}
