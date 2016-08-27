<?php

namespace Kiboko\Component\MagentoDriver\Model;

class Family implements FamilyInterface
{
    use MappableTrait;

    /**
     * @var int
     */
    private $identifier;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $sortOrder;

    /**
     * @param string $label
     * @param int    $sortOrder
     */
    public function __construct($label, $sortOrder = 1)
    {
        $this->label = $label;
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
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int    $familyId
     * @param string $label
     * @param int    $sortOrder
     *
     * @return FamilyInterface
     */
    public static function buildNewWith(
        $familyId,
        $label,
        $sortOrder = 1
    ) {
        $object = new static($label);

        $object->identifier = $familyId;
        $object->sortOrder = $sortOrder;

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
