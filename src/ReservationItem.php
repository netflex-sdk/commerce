<?php

namespace Netflex\Commerce;

use Netflex\Support\NetflexObject;

/**
 * @property int $cart_item
 * @property string $variant_id
 * @property string $reservation_start
 * @property string $reservation_end
 * @property array|object $reservation_length
 */
class ReservationItem extends NetflexObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'cart_item',
    'variant_id',
    'reservation_start',
    'reservation_end',
    'reservation_length'
  ];
}
