<?php

namespace Netflex\Commerce\Traits\API;

/* use Exception; */

use Netflex\API;
/* use Netflex\Commerce\Exceptions\OrderNotFoundException; */

trait Orders
{
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
