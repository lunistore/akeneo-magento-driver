<?php

namespace Luni\Component\MagentoDriver\AttributeValue;

use Doctrine\Common\Collections\Collection;
use League\Flysystem\File;
use Luni\Component\MagentoDriver\Attribute\AttributeInterface;

trait ImageMetadataAttributeValueTrait
{
    use AttributeValueTrait;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $excluded;

    /**
     * DatetimeAttributeValueTrait constructor.
     * @param AttributeInterface $attribute
     * @param string $label
     * @param int $position
     * @param bool $excluded
     * @param null $storeId
     */
    abstract public function __construct(
        AttributeInterface $attribute,
        $label,
        $position,
        $excluded = false,
        $storeId = null
    );

    /**
     * @param AttributeInterface $attribute
     * @param int $valueId
     * @param string $label
     * @param int $storeId
     * @param int $position
     * @param bool $excluded
     * @return ImageAttributeValueInterface
     */
    public static function buildNewWith(
        AttributeInterface $attribute,
        $valueId,
        $label,
        $position,
        $excluded = false,
        $storeId = null
    ) {
        $object = new static($attribute, $label, $position, $excluded, $storeId);

        $object->id = $valueId;

        return $object;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return $this->excluded;
    }
}