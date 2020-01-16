<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;
use Netflex\Commerce\Traits\API\DiscountItemAPI;

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
  use DiscountItemAPI;

  protected $readOnlyAttributes = [
    'discount_id'
  ];

  protected $requiredCreateAttributes = [
    'scope',
    'scope_key',
    'label',
    'type',
    'discount'
  ];
}
