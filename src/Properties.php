<?php

namespace Netflex\Commerce;

use Exception;
use Netflex\Support\NetflexObject;

class Properties extends NetflexObject
{
  public function jsonSerialize()
  {
    if (empty($this->attributes)) {
      return null;
    }

    return $this->attributes;
  }
}
