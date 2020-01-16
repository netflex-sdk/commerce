<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Order;

trait DiscountItemAPI
{
  /**
   * @throws Exception
   */
  public function delete()
  {
    API::getClient()
      ->delete(Order::basePath().$this->order_id.'/discount/'.$this->id);
  }
}
