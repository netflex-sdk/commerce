<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;

class PaymentItem extends ReactiveObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'id'
  ];
}
