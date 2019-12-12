<?php

namespace Netflex\Commerce;

use Netflex\Support\NetflexObject;

/**
 * @property-read int $receipt_order_id
 * @property-read int $receipt_shipping_id
 */
class Register extends NetflexObject
{
  /**
   * @param string|int $id
   * @return int
   */
  public function getReceiptOrderIdAttribute($id)
  {
    return (int) $id;
  }

  /**
   * @param string|int $id
   * @return int
   */
  public function getReceiptShippingIdAttribute($id)
  {
    return (int) $id;
  }
}
