<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;
use Netflex\Commerce\CartItem;

trait OrderAddItemAPI
{
  /**
   * @param array $item
   * @return static
   * @throws Exception
   */
  public function addCartItem($item)
  {
    if (!$this->id) {
      $this->save();
    }

    API::getClient()
      ->post(static::basePath().$this->id.'/cart', $item);

    return $this;
  }

  /**
   * @param array $item
   * @return static
   * @throws Exception
   */
  public function addPaymentItem($item)
  {
    if (!$this->id) {
      $this->save();
    }

    API::getClient()
      ->post(static::basePath().$this->id.'/payment', $item);

    return $this;
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogItemInformation($item)
  {
    return $this->addLogItem($item, 'n');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogItemWarning($item)
  {
    return $this->addLogItem($item, 'w');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogItemDanger($item)
  {
    return $this->addLogItem($item, 'd');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogItemSuccess($item)
  {
    return $this->addLogItem($item, 's');
  }

  /**
   * @param array|string $item
   * @param string $type
   * @return static
   * @throws Exception
   */
  public function addLogItem($item, $type = 'i')
  {
    if (!$this->id) {
      $this->save();
    }

    if (is_string($item)) {
      $item = ['msg' => $item];
    }

    $item['type'] = $item['type'] ?? $type;

    API::getClient()
      ->post(static::basePath().$this->id.'/log', $item);

    return $this;
  }
}
