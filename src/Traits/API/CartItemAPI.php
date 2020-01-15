<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;

trait CartItemAPI
{
  /**
   * @param array $updates
   * @return static
   * @throws Exception
   */
  public function save($updates = [])
  {
    foreach ($this->modified as $modifiedKey) {
      $updates[$modifiedKey] = $this->{$modifiedKey};
    }

    if (!empty($updates)) {
      API::getClient()
        ->put('commerce/orders/'.$this->order_id.'/cart/'.$this->id, $updates);
    }

    $this->modified = [];

    return $this;
  }

  /**
   * @throws Exception
   */
  public function delete()
  {
    API::getClient()
      ->delete('commerce/orders/'.$this->order_id.'/cart/'.$this->id);
  }
}
