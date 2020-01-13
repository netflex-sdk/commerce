<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Order;

trait Orders
{
  /**
   * @return $this
   * @throws Exception
   */
  public function save()
  {
    // TODO: Should payload contain something here?
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

    return $this->refresh();
  }

  /**
   * @return $this
   * @throws Exception
   */
  public function refresh()
  {
    $this->attributes = API::getClient()
      ->get(trim(static::$base_path, '/') . '/' . $this->id, true);

    return $this;
  }

  /**
   * @param string $key
   * @return $this
   */
  public function addToSession($key = 'netflex_cart')
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION[$key] = $this->secret;

    return $this;
  }

  /**
   * @param string $key
   * @return $this
   */
  public function removeFromSession($key = 'netflex_cart')
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION[$key] = null;

    return $this;
  }

  /**
   * Creates empty order object based on orderData
   *
   * @param array $order
   * @return Order
   * @throws Exception
   */
  public static function create($order = [])
  {
    return static::retrieve(
      API::getClient()
        ->post(trim(static::$base_path, '/'), $order)
        ->order_id
    );
  }

  /**
   * @param string $key
   * @return Order
   * @throws Exception
   */
  public static function retrieveBySessionOrCreate($key = 'netflex_cart')
  {
    $order = static::retrieveBySession($key);

    if (empty($order->id)) {
      $order->save()->addToSession();
    }

    return $order;
  }

  /**
   * @param string $key
   * @return Order
   * @throws Exception
   */
  public static function retrieveBySession($key = 'netflex_cart')
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if (isset($_SESSION[$key])) {
      return static::retrieveBySecret($_SESSION[$key]);
    }

    return new static();
  }

  /**
   * @param string $secret
   * @return Order
   * @throws Exception
   */
  public static function retrieveBySecret($secret)
  {
    return new static(
      API::getClient()
        ->get(trim(static::$base_path, '/') . '/secret/' . $secret)
    );
  }

  /**
   * @param string $id
   * @return Order
   * @throws Exception
   */
  public static function retrieveByRegisterId($id)
  {
    return new static(
      API::getClient()
        ->get(trim(static::$base_path, '/') . '/register/' . $id)
    );
  }

}
