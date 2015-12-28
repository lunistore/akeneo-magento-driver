<?php

namespace Luni\Component\MagentoDriver\AttributeValue\Immutable;

use Luni\Component\MagentoDriver\Attribute\AttributeInterface;
use Luni\Component\MagentoDriver\AttributeValue\Mutable\MutableVarcharAttributeValue;
use Luni\Component\MagentoDriver\AttributeValue\VarcharAttributeValueInterface;
use Luni\Component\MagentoDriver\AttributeValue\VarcharAttributeValueTrait;

class ImmutableVarcharAttributeValue
    implements ImmutableAttributeValueInterface, VarcharAttributeValueInterface
{
    use VarcharAttributeValueTrait;

    /**
     * MediaGalleryAttributeValue constructor.
     * @param AttributeInterface $attribute
     * @param string $payload
     * @param int $storeId
     */
    public function __construct(
        AttributeInterface $attribute,
        $payload,
        $storeId = null
    ) {
        $this->attribute = $attribute;
        $this->payload = $payload;
        $this->storeId = (int) $storeId;
    }

    /**
     * @return MutableVarcharAttributeValue
     */
    public function switchToMutable()
    {
        return MutableVarcharAttributeValue::buildNewWith(
            $this->attribute,
            $this->id,
            $this->payload,
            $this->storeId
        );
    }
}