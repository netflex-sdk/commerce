<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\CartItem;

trait Cart
{
  public function addCartItem(CartItem $item = null, $id = null)
  {
    $id = $id ?? $this->parent->id;
    API::getClient()
      ->post('commerce/orders/' . $id . '/cart', $item);
  }

  public function updateCartItem(CartItem $item = null, $id = null)
  {
    $id = $id ?? $this->parent->id;
    API::getClient()
      ->put('commerce/orders/' . $id . '/cart/' . $item->id, $item);
  }
}
