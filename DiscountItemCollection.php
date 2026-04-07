<?php

namespace Netflex\Commerce;

use Netflex\Support\ItemCollection;

/** @extends ItemCollection<array-key, DiscountItem> */
class DiscountItemCollection extends ItemCollection
{
  protected static $type = DiscountItem::class;
}
