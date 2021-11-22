<?php

namespace Netflex\Commerce;

use Netflex\Support\Retrievable;
use Netflex\Support\ReactiveObject;
use Netflex\Commerce\Traits\API\Orders as OrdersAPI;

/**
 * @property-read int $id
 * @property-read string $secret
 * @property float $order_tax
 * @property float $order_cost
 * @property float $order_total
 * @property int $customer_id
 * @property string $customer_code
 * @property string $customer_mail
 * @property string $customer_phone
 * @property string $created
 * @property string $updated
 * @property boolean $abandoned
 * @property boolean $abandoned_reminder_sent
 * @property string $abandoned_reminder_mail
 * @property-read Register $register
 * @property string $status
 * @property string $type
 * @property string $ip
 * @property string $user_agent
 * @property-read Cart $cart
 * @property-read Payments $payments
 * @property-read Data[] $data
 * @property-read LogItemCollection $log
 * @property-read Checkout[] $checkout
 */
class Order extends ReactiveObject
{
  use OrdersAPI;
  use Retrievable;

  /** @var string */
  protected static $base_path = 'commerce/orders';

  protected $defaults = [
    'cart' => null
  ];

  /**
   * @param string|int $id
   * @return int|null
   */
  public function getCustomerIdAttribute($id)
  {
    return $id ? (int) $id : $id;
  }

  /**
   * @param string|float|int $tax
   * @return float
   */
  public function getOrderTaxAttribute($tax)
  {
    return (float) $tax;
  }

  /**
   * @param string|float|int $cost
   * @return float
   */
  public function getOrderCostAttribute($cost)
  {
    return (float) $cost;
  }

  /**
   * @param string|float|int $total
   * @return float
   */
  public function getOrderTotalAttribute($total)
  {
    return (float) $total;
  }

  /**
   * @param string|int|boolean|null $abandoned
   * @return boolean
   */
  public function getAbandonedAttribute($abandoned)
  {
    return (bool) $abandoned;
  }

  /**
   * @param string|int|boolean|null $sent
   * @return boolean
   */
  public function getAbandonedReminderSentAttribute($sent)
  {
    return (bool) $sent;
  }

  /**
   * @param object|array|null $register
   * @return Register
   */
  public function getRegisterAttribute($register)
  {
    return Register::factory($register);
  }

  /**
   * @param object|array|null $value
   * @return Cart
   */
  public function getCartAttribute($cart = [])
  {
    return Cart::factory($cart, $this)
      ->addHook('modified', function (Cart $cart) {
        if (!$this->id) {
          $this->save();
        }

        foreach ($cart->items as $item) {
          $cart->addCartItem($item, $this->id);
        }
      });
  }

  /**
   * @param object|array|null $data
   * @return Data
   */
  public function getDataAttribute($data = [])
  {
    return Data::factory($data)
      ->addHook('modified', function (Data $data) {
        $this->__set('data', $data->jsonSerialize());
      });
  }

  /**
   * @param object|array|null $payments
   * @return Payments
   */
  public function getPaymentsAttribute($payments = null)
  {
    return Payments::factory($payments)
      ->addHook('modified', function (Payments $payments) {
        $this->__set('payments', $payments->jsonSerialize());
      });
  }

  /**
   * @param object|array|null $checkout
   * @return Checkout
   */
  public function getCheckoutAttribute($checkout = null)
  {
    return Checkout::factory($checkout, $this)
      ->addHook('modified', function (Checkout $checkout) {
        $this->__set('checkout', $checkout->jsonSerialize());
      });
  }

  /**
   * @param array|null $discounts
   * @return DiscountItemCollection
   */
  public function getDiscountsAttribute(array $discounts = [])
  {
    return DiscountItemCollection::factory($discounts, $this)
      ->addHook('modified', function (DiscountItemCollection $discounts) {
        $this->__set('discounts', $discounts->jsonSerialize());
      });
  }

  /**
   * @param array|null $log
   * @return LogItemCollection
   */
  public function getLogAttribute(array $log = [])
  {
    return LogItemCollection::factory($log)
      ->addHook('modified', function (LogItemCollection $log) {
        $this->__set('log', $log->jsonSerialize());
      });
  }
}
