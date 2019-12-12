<?php

namespace Netflex\Commerce;

use Netflex\Support\NetflexObject;

/**
 * @property-read int $id
 * @property int $entry_id
 * @property string $entry_name
 * @property int $no_of_entries
 * @property string $type
 * @property int $variant_id
 * @property string $variant_value
 * @property string $variant_name
 * @property float $variant_cost
 * @property int $variant_weight
 * @property float $tax_percent
 * @property string $added
 * @property-read string $updated
 * @property-read float $entries_cost
 * @property-read float $entries_tax
 * @property-read float $entries_total
 * @property-read DiscountItem[] $discounts
 * @property Properties $properties
 * @property string $ip
 * @property string $user_agent
 * @property bool $changed_in_cart
 * @property string $reservation_start
 * @property string $reservation_end
 * @property string $entries_comments
 * @property float $original_entries_cost
 * @property float $original_entries_tax
 * @property float $original_entries_total
 */
class CartItem extends NetflexObject
{
  /** @var array */
  protected $readOnlyAttributes = [
    'id',
    'entries_tax',
    'entries_cost',
    'entries_total',
    'discounts',
    'updated'
  ];

  /** @var array */
  protected $defaults = [
    'entry_id' => null,
    'entry_name' => null,
    'variant_cost' => null,
    'tax_percent' => null,
    'no_of_entries' => null
  ];

  /**
   * @param array|null $discounts
   * @return DiscountItemCollection
   */
  public function getDiscountsAttribute($discounts)
  {
    return DiscountItemCollection::factory($discounts)
      ->addHook('modified', function ($items) {
        $this->__set('discounts', $items->jsonSerialize());
      });
  }

  /**
   * @param array|object|null $properties
   * @return Properties
   */
  public function getPropertiesAttribute($properties)
  {
    return Properties::factory($properties, $this)
      ->addHook('modified', function ($propeties) {
        $this->__set('properties', $propeties->jsonSerialize());
      });
  }

  public function jsonSerialize()
  {
    $json = parent::jsonSerialize();
    $json['properties'] = $this->properties->jsonSerialize();

    return $json;
  }
}
