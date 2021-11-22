<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

/**
 * @property int $discount_id
 * @property string $scope
 * @property string $scope_key
 * @property string $label
 * @property string $type
 * @property float $discount
 */

class DiscountItem extends ReactiveObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'discount_id'
  ];

  /** @var array */
  protected $requiredCreateAttributes = [
    'scope',
    'scope_key',
    'label',
    'type',
    'discount'
  ];
}
