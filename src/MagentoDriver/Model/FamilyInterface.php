<?php

namespace Kiboko\Component\MagentoDriver\Model;

interface FamilyInterface extends MappableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return int
     */
    public function getSortOrder();
}
