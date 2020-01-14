<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\CartItem;

trait Cart
{
  /**
   * @param CartItem $item
   * @return static
   * @throws Exception
   */
  public function addCartItem(CartItem $item)
  {
    if (!$this->parent->id) {
      $this->parent->save();
    }

    API::getClient()
      ->post('commerce/orders/'.$this->parent->id.'/cart', $item);

    return $this;
  }

  /**
   * @param CartItem $item
   * @return static
   * @throws Exception
   */
  public function updateCartItem(CartItem $item)
  {
    API::getClient()
      ->put('commerce/orders/'.$this->parent->id.'/cart/'.$item->id, $item);

    return $this;
  }
}
