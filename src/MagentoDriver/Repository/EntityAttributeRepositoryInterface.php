<?php

namespace Kiboko\Component\MagentoDriver\Repository;

use Kiboko\Component\MagentoDriver\Model\EntityAttributeInterface;

interface EntityAttributeRepositoryInterface
{
    /**
     * @param int $identifier
     *
     * @return EntityAttributeInterface
     */
    public function findOneById($identifier);

    /**
     * return all statements.
     */
    public function findAll();

    /**
     * @param int $attributeId
     * @param int $attributeGroupId
     */
    public function findOneByAttributeIdAndGroupId($attributeId, $attributeGroupId);

    /**
     * @param type $attributeId
     * @param type $attributeSetId
     */
    public function findOneByAttributeIdAndSetId($attributeId, $attributeSetId);
}
