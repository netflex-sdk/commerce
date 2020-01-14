<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;
use Netflex\Commerce\Traits\API\Cart as CartAPI;

/**
 * @property-read int $id
 * @property-read CartItem[] $items
 * @property-read ReservationItem[] $reservations
 * @property-read float $discount
 * @property-read float $tax
 * @property-read float $total
 * @property-read float $weight
 * @property-read int $count_items
 * @property-read int $count_lines
 * @property-read float $sub_total
 */
class Cart extends ReactiveObject
{
  use CartAPI;

  protected $defaults = [
    'sub_total' => 0,
    'discount' => 0,
    'tax' => 0,
    'total' => 0,
    'weight' => 0,
    'count_items' => 0,
    'count_lines' => 0,
    'items' => [],
    'reservations' => [],
  ];

  protected $readOnlyAttributes = [
    'sub_total',
    'discount',
    'tax',
    'total',
    'weight',
    'count_items',
    'count_lines',
    'reservations'
  ];

  /**
   * @param mixed $items
   * @return CartItemCollection
   */
  public function getItemsAttribute($items = [])
  {
    return CartItemCollection::factory($items, $this)
      ->addHook('modified', function ($items) {
        $this->__set('items', $items->jsonSerialize());
      });
  }

  /**
   * @param mixed $items
   * @return ReservationItemCollection
   */
  public function getReservationsAttribute($items = [])
  {
    return ReservationItemCollection::factory($items)
      ->addHook('modified', function ($items) {
        $this->__set('reservations', $items->jsonSerialize());
      });
  }
}
