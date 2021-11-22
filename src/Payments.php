<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

/**
 * @property-read float $total
 * @property PaymentItemCollection $items
 */
class Payments extends ReactiveObject
{
  /** @var array */
  protected $readOnlyAttributes = ['total', 'items'];

  /**
   * @param array|null $items
   * @return PaymentItemCollection
   */
  public function getItemsAttribute(array $items)
  {
    return PaymentItemCollection::factory($items, $this)
      ->addHook('modified', function ($items) {
        $this->__set('items', $items->jsonSerialize());
      });
  }
}
