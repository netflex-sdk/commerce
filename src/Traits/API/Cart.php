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

    if (!$item->id) {
      API::getClient()
      ->post('commerce/orders/' . $id . '/cart', $item);

      return $this->parent->refresh();
    }

    return $this->updateCartItem($item, $id);
  }

  public function updateCartItem(CartItem $item = null, $id = null)
  {
    $id = $id ?? $this->parent->id;

    if ($item->properties->entry_id) {
      throw new Exception('fan');
    }

    API::getClient()
      ->put('commerce/orders/' . $id . '/cart/' . $item->id, $item);

    return $this->parent->refresh();
  }
}
