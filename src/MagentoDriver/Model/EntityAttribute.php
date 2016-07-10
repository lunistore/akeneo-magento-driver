<?php

namespace Kiboko\Component\MagentoDriver\Model;

class EntityAttribute implements EntityAttributeInterface
{
    /**
     * @var int
     */
    private $identifier;

    /**
     * @var int
     */
    private $entityTypeId;

    /**
     * @var int
     */
    private $attributeSetId;

    /**
     * @var int
     */
    private $attributeGroupId;

    /**
     * @var int
     */
    private $attributeId;

    /**
     * @var int
     */
    private $sortOrder;

    /**
     * @param int $entityTypeId
     * @param int $attributeSetId
     * @param int $attributeGroupId
     * @param int $attributeId
     * @param int $sortOrder
     */
    public function __construct(
        $entityTypeId,
        $attributeSetId,
        $attributeGroupId,
        $attributeId,
        $sortOrder = 1
    ) {
        $this->entityTypeId = $entityTypeId;
        $this->attributeSetId = $attributeSetId;
        $this->attributeGroupId = $attributeGroupId;
        $this->attributeId = $attributeId;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->identifier;
    }

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->entityTypeId;
    }

    /**
     * @return int
     */
    public function getAttributeSetId()
    {
        return $this->attributeSetId;
    }

    /**
     * @return int
     */
    public function getAttributeGroupId()
    {
        return $this->attributeGroupId;
    }

    /**
     * @return int
     */
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $entityAttributeId
     * @param int $entityTypeId
     * @param int $attributeSetId
     * @param int $attributeGroupId
     * @param int $attributeId
     * @param int $sortOrder
     *
     * @return EntityAttributeInterface
     */
    public static function buildNewWith(
        $entityAttributeId,
        $entityTypeId,
        $attributeSetId,
        $attributeGroupId,
        $attributeId,
        $sortOrder
    ) {
        $object = new static($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, $sortOrder);

        $object->identifier = $entityAttributeId;

        return $object;
    }

    /**
     * @param int $identifier
     */
    public function persistedToId($identifier)
    {
        $this->identifier = $identifier;
    }
}
