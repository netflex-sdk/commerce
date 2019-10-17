<?php

namespace Netflex\Commerce;

use Netflex\Support\ItemCollection;

class PaymentItemCollection extends ItemCollection
{
  /** @var string */
  protected static $type = PaymentItem::class;
}
