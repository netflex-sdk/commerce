<?php

namespace Netflex\Commerce;

use Netflex\Commerce\Traits\API\PaymentItemAPI;
use Netflex\Support\ReactiveObject;

class PaymentItem extends ReactiveObject
{
  use PaymentItemAPI;

  protected $readOnlyAttributes = [
    'id'
  ];
}
