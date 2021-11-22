<?php

namespace Netflex\Commerce;

use Netflex\Support\ItemCollection;

class CartItemCollection extends ItemCollection
{
  /** @var string */
  protected static $type = CartItem::class;
}
