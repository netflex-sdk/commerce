<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Exceptions\OrderNotFoundException;

trait OrderAPI
{
  use OrderAddItemAPI;

  /**
   * @param array $payload
   * @return static
   * @throws Exception
   */
  public function save($payload = [])
  {
    if (empty($payload)) {
      foreach ($this->modified as $modifiedKey) {
        $payload[$modifiedKey] = $this->{$modifiedKey};
      }
    }

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
      if (!empty($payload)) {
        API::getClient()
          ->put(trim(static::$base_path, '/').'/'.$this->id, $payload);

        $this->refresh();
      }
    }

    $this->modified = [];

    return $this;
  }

  /**
   * @return static
   * @throws Exception
   */
  public function refresh()
  {
    $this->attributes = API::getClient()
      ->get(trim(static::$base_path, '/').'/'.$this->id, true);

    return $this;
  }

  /**
   * @return static
   */
  public function addToSession()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION[static::$sessionKey] = $this->secret;

    return $this;
  }

  /**
   * @return static
   */
  public function removeFromSession()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    unset($_SESSION[static::$sessionKey]);

    return $this;
  }

  /**
   * @param array $payload
   * @return static
   * @throws Exception
   */
  public function checkout($payload = [])
  {
    API::getClient()
      ->put(trim(static::$base_path, '/').'/'.$this->id.'/checkout', $payload);

    return $this;
  }

  /**
   * Creates empty order object based on orderData
   *
   * @param array $order
   * @return static
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
   * If no session exist, it creates a new empty order in API with id and secret, and adds it to session.
   *
   * @param string|null $key
   * @return static
   * @throws Exception
   */
  public static function retrieveBySessionOrCreate($key = null)
  {
    $order = static::retrieveBySession($key);

    if (!$order->id) {
      $order->save()->addToSession();
    }

    return $order;
  }

  /**
   * If no session exist, it creates a new empty order object WITHOUT id or secret.
   * But; It makes shure session is set when order is saved.
   *
   * @param string|null $key
   * @return static
   * @throws Exception
   */
  public static function retrieveBySession($key = null)
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if ($key) {
      static::$sessionKey = $key;
    }

    if (isset($_SESSION[static::$sessionKey])) {
      try {
        $order = static::retrieveBySecret($_SESSION[static::$sessionKey]);

      } catch (OrderNotFoundException $e) {
        $order = new static();
        $order->removeFromSession();
        $order->triedReceivedBySession = true;
      }

    } else {
      $order = new static();
      $order->triedReceivedBySession = true;
    }

    return $order;
  }

  /**
   * @param string $secret
   * @return static
   * @throws Exception|OrderNotFoundException
   */
  public static function retrieveBySecret($secret)
  {
    $order = new static(
      API::getClient()
        ->get(trim(static::$base_path, '/').'/secret/'.$secret)
    );

    if (!$order->id) {
      throw new OrderNotFoundException('Order not found with secret '.$secret);
    }

    return $order;
  }

  /**
   * @param string $id
   * @return static
   * @throws Exception|OrderNotFoundException
   */
  public static function retrieveByRegisterId($id)
  {
    $order = new static(
      API::getClient()
        ->get(trim(static::$base_path, '/').'/register/'.$id)
    );

    if (!$order->id) {
      throw new OrderNotFoundException('Order not found with register id '.$id);
    }

    return $order;
  }

  /**
   * @param string $id
   * @return static
   * @throws Exception|OrderNotFoundException
   */
  public static function retrieve($id)
  {
    $order = new static(
      API::getClient()
        ->get(trim(static::$base_path, '/').'/'.$id)
    );

    if (!$order->id) {
      throw new OrderNotFoundException('Order not found with id '.$id);
    }

    return $order;
  }

}
