<?php

namespace Netflex\Commerce;

use Exception;
use Netflex\Support\NetflexObject;

class Properties extends NetflexObject
{
  public function jsonSerialize()
  {
    $attributes = empty($this->attributes) ? [] : $this->attributes;

    foreach ($attributes as $key => $value) {
      if (!is_string($value)) {
        unset($attributes[$key]);
      }
    }

    return $attributes;
  }
}
