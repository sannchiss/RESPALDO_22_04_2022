<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CustomDiscountExclusionDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property \FedEx\ShipService\SimpleType\RateDiscountType|string[] $ExcludedTypes
 */
class CustomDiscountExclusionDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CustomDiscountExclusionDetail';
    /**
     * Types of discounts that are excluded.
     *
     * @param \FedEx\ShipService\SimpleType\RateDiscountType[]|string[] $excludedTypes
     * @return $this
     */
    public function setExcludedTypes(array $excludedTypes)
    {
        $this->values['ExcludedTypes'] = $excludedTypes;
        return $this;
    }
}
