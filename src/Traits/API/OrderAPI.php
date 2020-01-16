<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Exceptions\OrderNotFoundException;

trait OrderAPI
{
  use OrderAddAPI;

  /**
   * @param $status
   * @return static
   * @throws Exception
   */
  public function saveStatus($status)
  {
    return $this->save(['status' => $status]);
  }

  /**
   * @param array $payload
   * @return static
   * @throws Exception
   */
  public function save($payload = [])
  {
    foreach ($this->modified as $modifiedKey) {
      $payload[$modifiedKey] = $this->{$modifiedKey};
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
          ->put(static::basePath().$this->id, $payload);

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
      ->get(static::basePath().$this->id, true);

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
   * Same as checkout, but set checkout_end date in payload for you
   * @param array $payload
   * @return static
   * @throws Exception
   */
  public function checkoutEnd($payload = [])
  {
    $payload['checkout_end'] = static::dateTimeNow();

    return $this->checkout($payload);
  }

  /**
   * @param array $payload
   * @return static
   * @throws Exception
   */
  public function checkout($payload = [])
  {
    API::getClient()
      ->put(static::basePath().$this->id.'/checkout', $payload);

    return $this;
  }

  /**
   * @return static
   * @throws Exception
   */
  public function register()
  {
    API::getClient()
      ->put(static::basePath().$this->id.'/register');

    return $this;
  }

  /**
   * Set order->status to "n" and checkout->checkout_end to now
   * @return static
   * @throws Exception
   */
  public function lock()
  {
    API::getClient()
      ->put(static::basePath().$this->id.'/lock');

    return $this;
  }

  /**
   * @return static
   * @throws Exception
   */
  public function emptyCart()
  {
    API::getClient()
      ->delete(static::basePath().$this->id.'/cart');

    return $this;
  }

  /**
   * @return static
   * @throws Exception
   */
  public function delete()
  {
    API::getClient()
      ->delete(static::basePath().$this->id);

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
        ->post(static::basePath(), $order)
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
        ->get(static::basePath().'secret/'.$secret)
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
        ->get(static::basePath().'register/'.$id)
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
        ->get(static::basePath().$id)
    );

    if (!$order->id) {
      throw new OrderNotFoundException('Order not found with id '.$id);
    }

    return $order;
  }

}
