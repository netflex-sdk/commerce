<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\CartItem;
use Netflex\Commerce\Order;

trait Orders
{
  /**
   * @return static
   * @throws Exception
   */
  public function save()
  {

    // TODO: Should payload contain something here?

    $payload = [];

    // Post new
    if (!$this->id) {
      $this->attributes['id'] = API::getClient()
        ->post(trim(static::$base_path, '/'), $payload)
        ->order_id;

      $this->refresh();

      if ($this->triedReceivedBySession) {
        $this->addToSession();
      }

    } else {
      // Put updates
      if (count($this->modified)) {
        API::getClient()
          ->put(trim(static::$base_path, '/') . '/' . $this->id, $payload);

        $this->refresh();
      }
    }

    return $this;
  }

  /**
   * @return static
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
   * @return static
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
   * @return static
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
   * @param array $item
   * @return static
   */
  public function addCartItem($item = [])
  {
    return $this->cart->addCartItem(CartItem::factory($item));
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

    $order = new static();
    $order->triedReceivedBySession = true;

    return $order;
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
