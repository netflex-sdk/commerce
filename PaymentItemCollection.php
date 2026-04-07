<?php

namespace Netflex\Commerce;

use Netflex\Support\ItemCollection;

/** @extends ItemCollection<array-key, PaymentItem> */
class PaymentItemCollection extends ItemCollection
{
  protected static $type = PaymentItem::class;
}
