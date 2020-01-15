<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\Order;

trait DataAPI
{
  /**
   * @param $key
   * @throws Exception
   */
  public function delete($key)
  {
    API::getClient()
      ->delete(Order::basePath().$this->parent->id.'/data/'.$key);
  }
}
