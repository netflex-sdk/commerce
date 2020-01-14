<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Order;

trait CartItemAPI
{
  /**
   * @param Order $order
   * @param array $updates
   * @return static
   * @throws Exception
   */
  public function save(Order $order, $updates = [])
  {
    if (empty($updates)) {
      foreach ($this->modified as $modifiedKey) {
        $updates[$modifiedKey] = $this->{$modifiedKey};
      }
    }

    if (!empty($updates)) {
      API::getClient()
        ->put('commerce/orders/'.$order->id.'/cart/'.$this->id, $updates);
    }

    return $this;
  }

  public function delete(Order $order)
  {
    API::getClient()
      ->delete('commerce/orders/'.$order->id.'/cart/'.$this->id);
  }
}
