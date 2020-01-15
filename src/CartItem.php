<?php

namespace Netflex\Commerce;

use Netflex\Support\ReactiveObject;
use Netflex\Commerce\Traits\API\CartItemAPI;

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
 * @property int $changed_in_cart
 * @property string $reservation_start
 * @property string $reservation_end
 * @property string $entries_comments
 * @property float $original_entries_cost
 * @property float $original_entries_tax
 * @property float $original_entries_total
 */
class CartItem extends ReactiveObject
{
  use CartItemAPI;

  protected $readOnlyAttributes = [
    'id',
    'entries_tax',
    'entries_cost',
    'entries_total',
    'discounts',
    'updated'
  ];

  protected $defaults = [
    'entry_id' => null,
    'entry_name' => null,
    'variant_cost' => null,
    'tax_percent' => null,
    'no_of_entries' => null
  ];

  protected $timestamps = [
    'added',
    'updated'
  ];

  public function getEntryIdAttribute($value)
  {
    return (int) $value;
  }

  public function getNoOfEntriesAttribute($value)
  {
    return (int) $value;
  }

  public function getVariantIdAttribute($value)
  {
    return (int) $value;
  }

  public function getVariantCostAttribute($value)
  {
    return (float) $value;
  }

  public function getTaxPercentAttribute($value)
  {
    return (float) $value;
  }

  public function getTaxCostAttribute($value)
  {
    return (float) $value;
  }

  public function getEntriesCostAttribute($value)
  {
    return (float) $value;
  }

  public function getEntriesTotalAttribute($value)
  {
    return (float) $value;
  }

  public function getChangedInCartAttribute($value)
  {
    return (int) $value;
  }

  /**
   * @param array|null $discounts
   * @return DiscountItemCollection
   */
  public function getDiscountsAttribute($discounts)
  {
    return DiscountItemCollection::factory($discounts, $this)
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
      ->addHook('modified', function ($properties) {
        $this->__set('properties', $properties->jsonSerialize());
      });
  }

  /**
   * @return array
   */
  public function jsonSerialize()
  {
    $json = parent::jsonSerialize();
    $json['properties'] = $this->properties->jsonSerialize();

    return $json;
  }
}
