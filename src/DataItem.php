<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

/**
 * @property string $data_alias
 * @property mixed $value
 * @property string $type
 * @property string $label
 * @property-read string $created
 * @property-read string $updated
 */
class DataItem extends ReactiveObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'created',
    'updated'
  ];
}
