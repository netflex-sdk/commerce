<?php

namespace Netflex\Commerce\Traits\API;

use Exception;
use Netflex\API;

trait OrderAddItemAPI
{
  /**
   * @param array $item
   * @return static
   * @throws Exception
   */
  public function addCart($item)
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
  public function addPayment($item)
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
  public function addLogInfo($item)
  {
    return $this->addLog($item, 'n');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogWarning($item)
  {
    return $this->addLog($item, 'w');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogDanger($item)
  {
    return $this->addLog($item, 'd');
  }

  /**
   * @param array|string $item
   * @return static
   * @throws Exception
   */
  public function addLogSuccess($item)
  {
    return $this->addLog($item, 's');
  }

  /**
   * @param array|string $item
   * @param string $type
   * @return static
   * @throws Exception
   */
  public function addLog($item, $type = 'i')
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
